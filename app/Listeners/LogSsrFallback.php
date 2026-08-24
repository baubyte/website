<?php

namespace App\Listeners;

use Illuminate\Support\Facades\Log;
use Inertia\Ssr\SsrRenderFailed;

/**
 * Observability for Inertia's automatic SSR fallback.
 *
 * `Inertia\Ssr\HttpGateway::dispatch()` already falls back to client-side
 * rendering by itself on ANY transport failure (connection refused,
 * timeout, non-2xx response, malformed payload, etc.) -- disabling the
 * `ssr` docker-compose service must leave the site fully functional with
 * zero code changes. We do NOT need to (and must not) hand-write fallback
 * middleware for that part.
 *
 * What IS missing without this listener is visibility: a silent fallback
 * to client-render is invisible in logs, which makes "is the ssr container
 * actually healthy in production" impossible to answer from Laravel's side
 * alone. `Inertia\Ssr\SsrRenderFailed` is the package's own officially
 * documented extension point for exactly this (see the "SSR Error
 * Handling" block in vendor/inertiajs/inertia-laravel/config/inertia.php
 * and `HttpGateway::handleSsrFailure()`, which dispatches this event on
 * every failure before optionally throwing).
 */
class LogSsrFallback
{
    public function handle(SsrRenderFailed $event): void
    {
        Log::warning('Inertia SSR render failed; falling back to client-side rendering.', $event->toArray());
    }
}
