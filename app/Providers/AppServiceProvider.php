<?php

namespace App\Providers;

use App\Listeners\LogSsrFallback;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\ServiceProvider;
use Inertia\Ssr\SsrRenderFailed;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        // PR10: log every time Inertia's SSR gateway falls back to
        // client-side rendering, so an `ssr` container outage is visible
        // in production logs instead of silently degrading. See
        // App\Listeners\LogSsrFallback's docblock for why this is the
        // officially-supported extension point rather than hand-rolled
        // fallback middleware.
        Event::listen(SsrRenderFailed::class, LogSsrFallback::class);
    }
}
