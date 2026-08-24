<?php

namespace App\Http\Middleware;

use App\Support\Locale\Locale;
use Illuminate\Http\Request;
use Inertia\Middleware;

class HandleInertiaRequests extends Middleware
{
    /**
     * The root template that's loaded on the first page visit.
     *
     * @see https://inertiajs.com/server-side-setup#root-template
     *
     * @var string
     */
    protected $rootView = 'app';

    /**
     * Determines the current asset version.
     *
     * @see https://inertiajs.com/asset-versioning
     */
    public function version(Request $request): ?string
    {
        return parent::version($request);
    }

    /**
     * Define the props that are shared by default.
     *
     * @see https://inertiajs.com/shared-data
     *
     * @return array<string, mixed>
     */
    public function share(Request $request): array
    {
        return [
            ...parent::share($request),
            // Shared globally (not just on `HomeController`) so
            // `LocaleSwitcher.svelte` can highlight the active language
            // from any future Inertia page, per PR9.
            'locale' => Locale::current(),
            // Public site key only -- the secret never leaves the server
            // (see `ChatMessageRequest::verifyTurnstileToken()`). Null
            // until the owner sets up a real Cloudflare Turnstile site,
            // same operational-dependency pattern as `services.n8n`.
            'turnstileSiteKey' => config('services.turnstile.site_key'),
        ];
    }
}
