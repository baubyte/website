<?php

namespace App\Actions\Chat;

use App\Http\Requests\ChatMessageRequest;
use App\Models\Lead;
use Illuminate\Http\Client\ConnectionException;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use Throwable;

/**
 * PR11 chat proxy (D8): the browser talks only to `ChatController`, which
 * delegates the actual work here -- it never talks to n8n directly, so the
 * webhook URL/secret never reach the client.
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
    /**
     * No `lang/` translation files exist for backend-owned strings in this
     * project (locale-suffixed `_es`/`_en` DB columns are the established
     * pattern instead, see `App\Support\Locale\Locale`) -- this small map
     * follows that same convention for the one user-facing string this
     * action owns.
     */
    private const array UNAVAILABLE_REPLY = [
        'es' => 'El chat no está disponible en este momento. Probá de nuevo en unos minutos.',
        'en' => 'Chat is unavailable right now. Please try again in a few minutes.',
    ];

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
                ->timeout(15)
                ->retry(1, 300)
                ->post($webhookUrl, $payload);
        } catch (ConnectionException|Throwable) {
            $this->logFailure(null, $startedAt);
            $this->recordLead($conversationId, $data, $page, $clientHash, 'failed');

            return $this->unavailableReply($locale, $conversationId);
        }

        $body = $response->json();

        if (! $response->successful() || ! is_array($body) || ! array_key_exists('reply', $body)) {
            $this->logFailure($response->status(), $startedAt);
            $this->recordLead($conversationId, $data, $page, $clientHash, 'failed');

            return $this->unavailableReply($locale, $conversationId);
        }

        $this->recordLead($conversationId, $data, $page, $clientHash, 'success');

        return new ChatReply(successful: true, reply: $body['reply'], conversationId: $conversationId);
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
     * `page` isn't pinned down further by the design/tasks docs beyond
     * "a reasonable value" -- the request may optionally supply it
     * directly (the widget knows its own current route), falling back to
     * the `Referer` header's path when it doesn't. Never the full URL
     * (query strings could carry PII), just the path.
     */
    private function resolvePage(ChatMessageRequest $request, array $data): ?string
    {
        if (! empty($data['page'])) {
            return $data['page'];
        }

        $referer = $request->headers->get('Referer');

        return $referer ? (parse_url($referer, PHP_URL_PATH) ?: null) : null;
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

    private function unavailableReply(string $locale, string $conversationId): ChatReply
    {
        return new ChatReply(
            successful: false,
            reply: self::UNAVAILABLE_REPLY[$locale] ?? self::UNAVAILABLE_REPLY['es'],
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
