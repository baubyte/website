<script>
    import LocaleSwitcher from '../Components/LocaleSwitcher.svelte';
    import { route } from '../lib/route.js';
    import { t } from '../lib/i18n.js';
    import Icon from '@iconify/svelte';
    import { iconEmailOutline } from '../lib/icons.js';

    /**
     * `locale` is passed down as a plain prop (not read from the Inertia
     * `page` store here) so this layout stays trivially testable in
     * isolation. In the real app it's the same value shared globally by
     * `HandleInertiaRequests::share()` — Inertia merges shared props into
     * every page component's own `$props()`, so `Home.svelte` (and any
     * future page) just forwards its own `locale` prop straight through.
     *
     * `profile` (optional) only feeds the nav's tagline
     * (`profile.specialty`, under the "Baubyte" wordmark) — it does not
     * widen this layout's responsibility beyond that single read, and any
     * consumer without a `profile` keeps working: the tagline line simply
     * doesn't render.
     */
    let { children, locale = 'es', profile = null } = $props();
</script>

<div class="min-h-screen bg-base-100 text-base-content">
    <header class="navbar sticky top-0 z-50 border-b border-base-300 bg-base-100/80 px-4 backdrop-blur">
        <div class="flex-1 min-w-0">
            <a href={route('home')} class="flex flex-col leading-tight">
                <span class="font-display text-lg font-bold text-base-content">Baubyte</span>
                {#if profile?.specialty}
                    <span class="hidden sm:inline font-display text-xs italic text-primary truncate">{profile.specialty}</span>
                {/if}
            </a>
        </div>
        <div class="flex-none flex items-center gap-1.5 sm:gap-2">
            <a
                href="#contact"
                class="btn btn-ghost btn-sm inline-flex items-center gap-1.5 font-medium hover:bg-base-200 hover:text-primary px-2 sm:px-3"
                aria-label={t('hero.contact')}
            >
                <Icon icon={iconEmailOutline} width="18" height="18" class="shrink-0" />
                <span class="hidden sm:inline text-sm">{t('hero.contact')}</span>
            </a>

            <div class="flex items-center rounded-full border border-base-300 bg-base-100/60 px-1.5 py-0.5">
                <LocaleSwitcher currentLocale={locale} />
            </div>
        </div>
    </header>

    <main>
        {@render children()}
    </main>
</div>
