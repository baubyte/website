<?php

namespace App\Actions\Chat;

use App\Http\Requests\ChatMessageRequest;
use App\Models\Lead;
use Illuminate\Http\Client\ConnectionException;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use Illuminate\Support\Uri;
use Throwable;

/**
 * The browser talks only to `ChatController`, which delegates the actual
 * work here -- it never talks to n8n directly, so the webhook URL/secret
 * never reach the client.
 *
 * Browser --POST /api/chat--> throttle:20,1 -> EnsureSameOrigin ->
 * ChatController -> SendChatMessage --Http (15s)--> n8n webhook
 * (X-Webhook-Secret)
 *
 * A `Lead` row is persisted for every validated chat message, whether the
 * n8n call succeeds or not (`reply_status`) -- this is the lead-capture
 * record the owner reviews later, replacing the old contact form. Only an
 * HMAC of the visitor's IP/User-Agent (`client_hash`) is ever stored or
 * forwarded to n8n; the raw IP/User-Agent never leaves this class.
 */
class SendChatMessage
{
    public function handle(ChatMessageRequest $request): ChatReply
    {
        $data = $request->validated();

        $conversationId = $data['conversation_id'] ?? (string) Str::uuid();
        $locale = $data['locale'];
        $page = $this->resolvePage($request, $data);
        $clientHash = $this->clientHash($request);

        if ($this->dailyLimitReached()) {
            $this->recordLead($conversationId, $data, $page, $clientHash, 'blocked_daily_limit');

            return $this->unavailableReply($locale, $conversationId);
        }

        $webhookUrl = config('services.n8n.chat_webhook');
        $secret = config('services.n8n.secret');

        if (empty($webhookUrl) || empty($secret)) {
            $this->recordLead($conversationId, $data, $page, $clientHash, 'failed');

            return $this->unavailableReply($locale, $conversationId);
        }

        $payload = [
            'conversation_id' => $conversationId,
            'message' => $data['message'],
            'locale' => $locale,
            'page' => $page,
            'sent_at' => now()->toIso8601String(),
            'client_hash' => $clientHash,
        ];

        $startedAt = microtime(true);

        try {
            $response = Http::withHeaders([
                'X-Webhook-Secret' => $secret,
            ])
                ->connectTimeout(5)
                ->timeout(30)
                ->retry(1, 300)
                ->post($webhookUrl, $payload);
        } catch (ConnectionException|Throwable) {
            $this->logFailure(null, $startedAt);
            $this->recordLead($conversationId, $data, $page, $clientHash, 'failed');

            return $this->unavailableReply($locale, $conversationId);
        }

        $reply = $response->json('reply');

        if (! $response->successful() || ! is_string($reply) || $reply === '') {
            $this->logFailure($response->status(), $startedAt);
            $this->recordLead($conversationId, $data, $page, $clientHash, 'failed');

            return $this->unavailableReply($locale, $conversationId);
        }

        $this->recordLead($conversationId, $data, $page, $clientHash, 'success');

        return new ChatReply(successful: true, reply: $reply, conversationId: $conversationId);
    }

    /**
     * Cost/abuse guard: a site-wide hard cap on messages forwarded to n8n
     * per calendar day (`services.chat.daily_limit`), independent of the
     * per-IP `throttle:20,1` on the route -- that limit resets every
     * minute per IP and does nothing against a patient single script or a
     * handful of rotating IPs. Counts every `Lead` row created today
     * (any `reply_status`, including past `blocked_daily_limit` ones) so
     * the cap can't be reset mid-day by triggering failures on purpose.
     */
    private function dailyLimitReached(): bool
    {
        $limit = (int) config('services.chat.daily_limit');

        if ($limit <= 0) {
            return false;
        }

        return Lead::whereDate('created_at', now()->toDateString())->count() >= $limit;
    }

    /**
     * `page` may be supplied directly (the widget knows its own current
     * route), falling back to the `Referer` header's path when it isn't.
     * Never the full URL (query strings could carry PII), just the path.
     *
     * `Uri::path()` trims leading/trailing slashes and returns `/` for an
     * empty path (never null) -- re-adding the leading slash keeps this
     * consistent with the widget's own `window.location.pathname`, which
     * always has one. `Referer` is attacker-controlled like any header, and
     * `Uri::of()` throws on a genuinely malformed one (unlike `parse_url()`,
     * which just returns false) -- caught so a bad header degrades to "no
     * page" instead of a 500.
     */
    private function resolvePage(ChatMessageRequest $request, array $data): ?string
    {
        if (! empty($data['page'])) {
            return $data['page'];
        }

        $referer = $request->headers->get('Referer');

        if (! $referer) {
            return null;
        }

        try {
            return '/'.ltrim(Uri::of($referer)->path(), '/');
        } catch (Throwable) {
            return null;
        }
    }

    /**
     * The raw IP/User-Agent are read here and NEVER stored or forwarded --
     * only this HMAC digest is, both to n8n and into the `leads` table.
     */
    private function clientHash(ChatMessageRequest $request): string
    {
        $ip = (string) $request->ip();
        $userAgent = (string) $request->userAgent();

        return hash_hmac('sha256', $ip.'|'.$userAgent, (string) config('app.key'));
    }

    private function recordLead(
        string $conversationId,
        array $data,
        ?string $page,
        string $clientHash,
        string $replyStatus,
    ): void {
        Lead::create([
            'conversation_id' => $conversationId,
            'message' => $data['message'],
            'locale' => $data['locale'],
            'page' => $page,
            'client_hash' => $clientHash,
            'reply_status' => $replyStatus,
        ]);
    }

    /**
     * `$locale` is the validated request's own `locale` field
     * (`ChatMessageRequest::rules()`, `in:es,en`), not necessarily the
     * current session locale -- `__()`'s explicit third argument
     * translates for that exact locale regardless of what the app/session
     * is currently resolved to.
     */
    private function unavailableReply(string $locale, string $conversationId): ChatReply
    {
        return new ChatReply(
            successful: false,
            reply: __('front.chat.unavailable', [], $locale),
            conversationId: $conversationId,
        );
    }

    /**
     * Only status code + duration -- never the upstream body, the webhook
     * URL/secret, or the raw IP/User-Agent.
     */
    private function logFailure(?int $status, float $startedAt): void
    {
        Log::warning('n8n chat webhook call failed', [
            'status' => $status,
            'duration_ms' => (int) round((microtime(true) - $startedAt) * 1000),
        ]);
    }
}
