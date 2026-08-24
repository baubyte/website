<?php

namespace Tests\Feature\Chat;

use App\Models\Lead;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\Client\ConnectionException;
use Illuminate\Http\Client\Request as ClientRequest;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Sleep;
use Illuminate\Testing\TestResponse;
use Tests\TestCase;

/**
 * PR11 chat proxy (D8: `ChatController` proxies Laravel -> n8n, the
 * browser never talks to n8n directly). Verbatim from the design doc's
 * testing-strategy section: "ChatController (403 origen ajeno, 429
 * throttle, 503 con Http::fake timeout, secreto nunca en el body)".
 */
class ChatControllerTest extends TestCase
{
    use RefreshDatabase;

    private const WEBHOOK_URL = 'https://n8n.example.test/webhook/chat-secret-path';

    private const WEBHOOK_SECRET = 'super-secret-n8n-token';

    protected function setUp(): void
    {
        parent::setUp();

        Config::set('app.url', 'https://example.test');
        Config::set('services.n8n.chat_webhook', self::WEBHOOK_URL);
        Config::set('services.n8n.secret', self::WEBHOOK_SECRET);
    }

    /** @param  array<string, mixed>  $overrides */
    private function validPayload(array $overrides = []): array
    {
        return array_merge([
            'message' => 'Hola, ¿cómo estás?',
            'locale' => 'es',
        ], $overrides);
    }

    /**
     * @param  array<string, mixed>  $payload
     * @param  array<string, string>  $headers
     */
    private function postChat(array $payload, array $headers = []): TestResponse
    {
        return $this->withHeaders(array_merge([
            'Origin' => 'https://example.test',
        ], $headers))->postJson('/api/chat', $payload);
    }

    public function test_mismatched_origin_is_rejected_with_403_and_never_calls_n8n(): void
    {
        Http::fake();

        $response = $this->postChat($this->validPayload(), ['Origin' => 'https://evil.test']);

        $response->assertStatus(403);
        Http::assertNothingSent();
    }

    public function test_missing_origin_and_referer_is_rejected_with_403_and_never_calls_n8n(): void
    {
        Http::fake();

        // Bypasses postChat()'s default Origin header on purpose.
        $response = $this->postJson('/api/chat', $this->validPayload());

        $response->assertStatus(403);
        Http::assertNothingSent();
    }

    public function test_falls_back_to_referer_host_when_origin_header_is_absent(): void
    {
        Http::fake(['*' => Http::response(['reply' => 'Hola!'], 200)]);

        $response = $this->withHeaders([
            'Referer' => 'https://example.test/some-page',
        ])->postJson('/api/chat', $this->validPayload());

        $response->assertOk();
    }

    public function test_exceeding_the_throttle_limit_responds_with_429(): void
    {
        Http::fake(['*' => Http::response(['reply' => 'Hola!'], 200)]);

        for ($i = 0; $i < 20; $i++) {
            $this->postChat($this->validPayload())->assertStatus(200);
        }

        $this->postChat($this->validPayload())->assertStatus(429);
    }

    public function test_n8n_timeout_responds_with_503_and_never_leaks_the_webhook_url_or_secret(): void
    {
        Sleep::fake();
        Http::fake(function () {
            throw new ConnectionException('Connection timed out');
        });

        $response = $this->postChat($this->validPayload());

        $response->assertStatus(503);
        $response->assertExactJson([
            'error' => 'chat_unavailable',
            'reply' => 'El chat no está disponible en este momento. Probá de nuevo en unos minutos.',
        ]);

        $body = $response->getContent();
        $this->assertStringNotContainsString(self::WEBHOOK_URL, (string) $body);
        $this->assertStringNotContainsString(self::WEBHOOK_SECRET, (string) $body);
    }

    public function test_n8n_non_2xx_response_responds_with_503_and_never_leaks_upstream_body(): void
    {
        Sleep::fake();
        Http::fake(['*' => Http::response('some upstream error detail', 500)]);

        $response = $this->postChat($this->validPayload());

        $response->assertStatus(503);
        $response->assertExactJson([
            'error' => 'chat_unavailable',
            'reply' => 'El chat no está disponible en este momento. Probá de nuevo en unos minutos.',
        ]);
        $this->assertStringNotContainsString('some upstream error detail', (string) $response->getContent());
    }

    public function test_successful_n8n_response_returns_200_with_reply_and_conversation_id(): void
    {
        Http::fake(['*' => Http::response(['reply' => 'Hola! Soy el bot.'], 200)]);

        $response = $this->postChat($this->validPayload());

        $response->assertOk();
        $response->assertJsonStructure(['reply', 'conversation_id']);
        $response->assertJson(['reply' => 'Hola! Soy el bot.']);
    }

    public function test_missing_webhook_url_config_responds_with_503_immediately_without_calling_n8n(): void
    {
        Config::set('services.n8n.chat_webhook', '');
        Http::fake();

        $response = $this->postChat($this->validPayload());

        $response->assertStatus(503);
        $response->assertJson(['error' => 'chat_unavailable']);
        Http::assertNothingSent();
    }

    public function test_missing_secret_config_responds_with_503_immediately_without_calling_n8n(): void
    {
        Config::set('services.n8n.secret', null);
        Http::fake();

        $response = $this->postChat($this->validPayload());

        $response->assertStatus(503);
        $response->assertJson(['error' => 'chat_unavailable']);
        Http::assertNothingSent();
    }

    public function test_message_too_long_fails_validation_with_422(): void
    {
        Http::fake();

        $response = $this->postChat($this->validPayload(['message' => str_repeat('a', 2001)]));

        $response->assertStatus(422);
        $response->assertJsonValidationErrors('message');
        Http::assertNothingSent();
    }

    public function test_invalid_locale_fails_validation_with_422(): void
    {
        Http::fake();

        $response = $this->postChat($this->validPayload(['locale' => 'fr']));

        $response->assertStatus(422);
        $response->assertJsonValidationErrors('locale');
        Http::assertNothingSent();
    }

    public function test_invalid_conversation_id_format_fails_validation_with_422(): void
    {
        Http::fake();

        $response = $this->postChat($this->validPayload(['conversation_id' => 'not-a-uuid']));

        $response->assertStatus(422);
        $response->assertJsonValidationErrors('conversation_id');
        Http::assertNothingSent();
    }

    public function test_a_lead_row_is_created_for_a_successful_chat_interaction(): void
    {
        Http::fake(['*' => Http::response(['reply' => 'Hola!'], 200)]);

        $this->postChat($this->validPayload(['message' => 'Necesito un presupuesto']));

        $this->assertDatabaseHas('leads', [
            'message' => 'Necesito un presupuesto',
            'reply_status' => 'success',
        ]);
    }

    public function test_a_lead_row_is_created_for_a_failed_chat_interaction(): void
    {
        Sleep::fake();
        Http::fake(['*' => Http::response('nope', 500)]);

        $this->postChat($this->validPayload(['message' => 'Otro mensaje']));

        $this->assertDatabaseHas('leads', [
            'message' => 'Otro mensaje',
            'reply_status' => 'failed',
        ]);
    }

    public function test_persisted_lead_never_contains_raw_ip_or_user_agent(): void
    {
        Http::fake(['*' => Http::response(['reply' => 'Hola!'], 200)]);

        $this->postChat($this->validPayload(), ['User-Agent' => 'ExplicitTestAgent/1.0']);

        $lead = Lead::first();
        $this->assertNotNull($lead);

        $rawRow = json_encode($lead->getAttributes());

        $this->assertIsString($rawRow);
        $this->assertStringNotContainsString('127.0.0.1', $rawRow);
        $this->assertStringNotContainsString('ExplicitTestAgent', $rawRow);
        $this->assertNotEmpty($lead->client_hash);
    }

    public function test_outbound_payload_sent_to_n8n_carries_an_hmac_client_hash_not_the_raw_ip_or_user_agent(): void
    {
        Http::fake(['*' => Http::response(['reply' => 'Hola!'], 200)]);

        $this->postChat($this->validPayload(), ['User-Agent' => 'ExplicitTestAgent/1.0']);

        $expectedHash = hash_hmac(
            'sha256',
            '127.0.0.1|ExplicitTestAgent/1.0',
            (string) config('app.key')
        );

        Http::assertSent(function (ClientRequest $request) use ($expectedHash) {
            $data = $request->data();
            $rawJson = (string) $request->body();

            return $request->hasHeader('X-Webhook-Secret', self::WEBHOOK_SECRET)
                && ($data['client_hash'] ?? null) === $expectedHash
                && ! str_contains($rawJson, 'ExplicitTestAgent')
                && ! str_contains($rawJson, '127.0.0.1');
        });
    }

    public function test_daily_message_limit_blocks_further_sends_without_calling_n8n_and_records_the_lead(): void
    {
        Config::set('services.chat.daily_limit', 2);
        Http::fake(['*' => Http::response(['reply' => 'Hola!'], 200)]);

        $this->postChat($this->validPayload())->assertOk();
        $this->postChat($this->validPayload())->assertOk();

        Http::fake(['*' => Http::response(['reply' => 'Hola!'], 200)]);
        $response = $this->postChat($this->validPayload(['message' => 'mensaje de más']));

        $response->assertStatus(503);
        $response->assertJson(['error' => 'chat_unavailable']);
        Http::assertSentCount(0);

        $this->assertDatabaseHas('leads', [
            'message' => 'mensaje de más',
            'reply_status' => 'blocked_daily_limit',
        ]);
    }

    public function test_daily_limit_of_zero_means_no_cap(): void
    {
        Config::set('services.chat.daily_limit', 0);
        Http::fake(['*' => Http::response(['reply' => 'Hola!'], 200)]);

        for ($i = 0; $i < 5; $i++) {
            $this->postChat($this->validPayload())->assertOk();
        }
    }

    public function test_valid_turnstile_token_is_accepted_when_turnstile_is_configured(): void
    {
        Config::set('services.turnstile.secret_key', 'ts-secret');
        Http::fake([
            'challenges.cloudflare.com/*' => Http::response(['success' => true], 200),
            self::WEBHOOK_URL => Http::response(['reply' => 'Hola!'], 200),
        ]);

        $response = $this->postChat($this->validPayload(['turnstile_token' => 'a-real-looking-token']));

        $response->assertOk();
    }

    public function test_missing_turnstile_token_is_rejected_with_422_when_turnstile_is_configured(): void
    {
        Config::set('services.turnstile.secret_key', 'ts-secret');
        Http::fake();

        $response = $this->postChat($this->validPayload());

        $response->assertStatus(422);
        $response->assertJsonValidationErrors('turnstile_token');
        Http::assertNotSent(fn (ClientRequest $request) => $request->url() === self::WEBHOOK_URL);
    }

    public function test_invalid_turnstile_token_is_rejected_with_422_when_turnstile_is_configured(): void
    {
        Config::set('services.turnstile.secret_key', 'ts-secret');
        Http::fake([
            'challenges.cloudflare.com/*' => Http::response(['success' => false], 200),
        ]);

        $response = $this->postChat($this->validPayload(['turnstile_token' => 'a-fake-token']));

        $response->assertStatus(422);
        $response->assertJsonValidationErrors('turnstile_token');
        Http::assertNotSent(fn (ClientRequest $request) => $request->url() === self::WEBHOOK_URL);
    }
}
