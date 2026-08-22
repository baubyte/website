<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Log;
use Illuminate\Testing\TestResponse;
use Inertia\Testing\AssertableInertia as Assert;
use Tests\TestCase;

/**
 * PR10 (SSR + Docker) rollback-boundary contract: disabling/losing the
 * `ssr` docker-compose service must still serve a fully working site via
 * Inertia's own automatic client-side-render fallback, with zero code
 * changes required to roll back (see the SDD design doc's D9).
 *
 * `Inertia\Ssr\HttpGateway::dispatch()` already implements the fallback
 * mechanism itself on any transport failure -- this suite does NOT
 * hand-roll fallback middleware, it verifies the contract that fallback
 * relies on:
 *
 *   1. The page still renders (200, correct Inertia component/props) when
 *      SSR is disabled outright -- this app's actual local/DDEV default
 *      (see `.env.example`'s `INERTIA_SSR_ENABLED=false`; DDEV never runs
 *      the `ssr` service).
 *   2. The page still renders the same way when SSR is *enabled* but the
 *      configured `ssr` endpoint is unreachable (the production "container
 *      is down" case) -- and that failure gets logged via
 *      `App\Listeners\LogSsrFallback`, the officially-documented
 *      `Inertia\Ssr\SsrRenderFailed` extension point.
 *   3. Crucially: the JSON-LD `<script type="application/ld+json">` block
 *      is present in BOTH cases. It's deliberately Blade-rendered from
 *      `config/seo.php`/`App\Support\Seo\PersonSchema` (see
 *      `resources/views/partials/seo.blade.php`'s docblock), not emitted
 *      via `<svelte:head>`, specifically so crawlers still see structured
 *      data no matter how the page was rendered. This is checked against
 *      the raw HTTP response body, not via `assertInertia()`, since
 *      JSON-LD lives outside the Inertia page-data payload entirely.
 */
class SsrFallbackTest extends TestCase
{
    use RefreshDatabase;

    public function test_home_page_renders_via_client_side_fallback_when_ssr_is_disabled(): void
    {
        Config::set('inertia.ssr.enabled', false);

        $response = $this->get('/');

        $response->assertOk();
        $this->assertRendersHomePage($response);
        $this->assertJsonLdPresent($response);
    }

    public function test_home_page_renders_via_client_side_fallback_and_logs_when_ssr_is_unreachable(): void
    {
        Log::spy();

        // Simulates the `ssr` docker-compose service being down/unreachable
        // in production: SSR is enabled, but nothing is listening at the
        // configured URL. 127.0.0.1:1 refuses the connection immediately
        // (no DNS lookup, no timeout wait), landing in exactly the
        // "connection" failure branch `HttpGateway::dispatch()` catches
        // before falling back to client-side rendering.
        Config::set('inertia.ssr.enabled', true);
        Config::set('inertia.ssr.url', 'http://127.0.0.1:1');
        // No `bootstrap/ssr/ssr.js` build artifact exists in this test
        // environment. Without disabling this check, `HttpGateway::
        // dispatch()` would bail out on the missing-bundle guard BEFORE
        // ever attempting the HTTP call -- silently short-circuiting the
        // exact connection-failure/fallback/logging path this test exists
        // to exercise, and turning it into a false positive.
        Config::set('inertia.ssr.ensure_bundle_exists', false);

        $response = $this->get('/');

        $response->assertOk();
        $this->assertRendersHomePage($response);
        $this->assertJsonLdPresent($response);

        // At least once, not an exact count: how many times Inertia's own
        // internals touch the gateway per request (e.g. once for
        // `@inertiaHead`, once for `@inertia`'s body) is an implementation
        // detail of the vendor package, not part of this app's fallback
        // contract -- what matters here is that a real connection failure
        // is never silently swallowed.
        Log::shouldHaveReceived('warning')
            ->withArgs(fn (string $message) => str_contains(
                $message,
                'Inertia SSR render failed'
            ));
    }

    private function assertRendersHomePage(TestResponse $response): void
    {
        $response->assertInertia(fn (Assert $page) => $page
            // `pages.paths` in config/inertia.php points at
            // `resource_path('js/pages')` (lowercase) while the real
            // directory is `resources/js/Pages` (capital P, see
            // HomeControllerTest) -- shouldExist: false sidesteps that
            // pre-existing case mismatch rather than re-litigating it here.
            ->component('Home', shouldExist: false)
            ->has('profile')
            ->has('skills')
            ->has('experiences')
            ->has('studies')
            ->has('yearsOfExperience')
        );
    }

    private function assertJsonLdPresent(TestResponse $response): void
    {
        $content = $response->getContent();

        $this->assertIsString($content);
        $this->assertStringContainsString('application/ld+json', $content);

        preg_match('#<script type="application/ld\+json">(.*?)</script>#s', $content, $matches);
        $this->assertNotEmpty($matches, 'Expected a JSON-LD <script> block in the response body.');

        $jsonLd = json_decode(trim($matches[1]), true);

        $this->assertIsArray($jsonLd);
        $this->assertSame('https://schema.org', $jsonLd['@context'] ?? null);
        $this->assertSame('Person', $jsonLd['@type'] ?? null);
    }
}
