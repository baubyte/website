<script>
    import { route } from '../lib/route.js';
    import { t } from '../lib/i18n.js';

    /**
     * Presentational language switcher. Deliberately renders plain
     * `<a href="/locale/{code}">` full-page navigations rather than
     * `router.visit` from Inertia: `GET /locale/{locale}` is a session-based
     * write (see `App\Http\Controllers\LocaleController`) that must reach
     * the server and reload so the Blade-rendered `<html lang>`/SEO
     * meta/JSON-LD (see `resources/views/partials/seo.blade.php`) refresh
     * along with the Inertia page in a single trip — the same full-reload
     * behavior the legacy app's own `LocaleController::set()` had.
     *
     * `currentLocale` is passed in by the caller (see `FrontLayout.svelte`,
     * which reads it from the `locale` prop shared globally by
     * `HandleInertiaRequests::share()`) rather than read from the Inertia
     * `page` store here, keeping this component trivially unit-testable.
     */
    let { currentLocale = 'es' } = $props();

    const LOCALES = [
        { code: 'es', label: 'ES' },
        { code: 'en', label: 'EN' },
    ];
</script>

<nav aria-label={t('nav.language_selector')} class="flex gap-1">
    {#each LOCALES as { code, label } (code)}
        <a
            href={route('locale', { locale: code })}
            class="btn btn-ghost btn-xs"
            class:btn-active={currentLocale === code}
            aria-current={currentLocale === code ? 'true' : undefined}
        >
            {label}
        </a>
    {/each}
</nav>
