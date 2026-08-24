<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Uri;
use Symfony\Component\HttpFoundation\Response;

/**
 * Chat proxy guard. `POST /api/chat` is a same-origin-only endpoint:
 * the `Origin` header's host (falling back to `Referer`'s host when
 * `Origin` is absent, as browsers may omit `Origin` on same-origin
 * requests in some configurations) must match `config('app.url')`'s host.
 * A mismatch -- or both headers missing -- is rejected with 403 BEFORE the
 * request ever reaches `ChatController`, so a cross-origin caller can never
 * trigger the outbound n8n call.
 *
 * This is a defense-in-depth check alongside CSRF (the `web` middleware
 * group + Inertia's `X-XSRF-TOKEN` already block cross-site POSTs); it
 * exists specifically to keep the n8n webhook call itself same-origin-only.
 */
class EnsureSameOrigin
{
    public function handle(Request $request, Closure $next): Response
    {
        $originHost = $this->hostFromHeader($request->headers->get('Origin'))
            ?? $this->hostFromHeader($request->headers->get('Referer'));

        $expectedHost = Uri::of((string) config('app.url'))->host();

        if ($originHost === null || $originHost !== $expectedHost) {
            abort(403);
        }

        return $next($request);
    }

    /**
     * `Uri::of()` (unlike `parse_url()`) throws on a genuinely malformed
     * URI instead of just returning an empty part -- an attacker-controlled
     * `Origin`/`Referer` header must never be able to turn this same-origin
     * check into an unhandled 500 instead of the intended clean 403.
     */
    private function hostFromHeader(?string $header): ?string
    {
        if (! $header) {
            return null;
        }

        try {
            return Uri::of($header)->host();
        } catch (\Throwable) {
            return null;
        }
    }
}
