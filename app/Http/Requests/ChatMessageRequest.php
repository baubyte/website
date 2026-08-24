<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\Validator as ValidatorContract;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

/**
 * `POST /api/chat` chat proxy. No per-user auth exists on this public
 * endpoint -- `EnsureSameOrigin` + `throttle:20,1` (see `routes/web.php`)
 * are the actual access controls, so `authorize()` just clears every
 * request that reaches validation.
 */
class ChatMessageRequest extends FormRequest
{
    private const TURNSTILE_VERIFY_URL = 'https://challenges.cloudflare.com/turnstile/v0/siteverify';

    public function authorize(): bool
    {
        return true;
    }

    /**
     * `turnstile_token` is only `required` when `services.turnstile.secret_key`
     * is configured -- until the owner sets up a real Cloudflare Turnstile
     * site (see `after()`), this stays optional so local dev keeps working
     * with zero setup, same fail-open-in-dev pattern as `services.n8n`.
     *
     * @return array<string, array<int, string>>
     */
    public function rules(): array
    {
        return [
            'message' => ['required', 'string', 'max:2000'],
            'conversation_id' => ['nullable', 'uuid'],
            'locale' => ['required', 'in:es,en'],
            'page' => ['nullable', 'string', 'max:255'],
            'turnstile_token' => [
                filled(config('services.turnstile.secret_key')) ? 'required' : 'nullable',
                'string',
            ],
        ];
    }

    /**
     * @return array<int, callable>
     */
    public function after(): array
    {
        return [
            function (ValidatorContract $validator) {
                $secret = config('services.turnstile.secret_key');

                if (blank($secret)) {
                    return;
                }

                if (! $this->verifyTurnstileToken($secret)) {
                    $validator->errors()->add('turnstile_token', 'The bot check failed.');
                }
            },
        ];
    }

    private function verifyTurnstileToken(string $secret): bool
    {
        try {
            $response = Http::asForm()
                ->connectTimeout(3)
                ->timeout(5)
                ->retry(1, 200)
                ->post(self::TURNSTILE_VERIFY_URL, [
                    'secret' => $secret,
                    'response' => (string) $this->turnstile_token,
                    'remoteip' => $this->ip(),
                ]);
        } catch (\Throwable) {
            Log::warning('Turnstile verification request failed');

            return false;
        }

        return $response->successful() && $response->json('success') === true;
    }
}
