<script>
    import LocaleSwitcher from '../Components/LocaleSwitcher.svelte';

    /**
     * `locale` is passed down as a plain prop (not read from the Inertia
     * `page` store here) so this layout stays trivially testable in
     * isolation. In the real app it's the same value shared globally by
     * `HandleInertiaRequests::share()` — Inertia merges shared props into
     * every page component's own `$props()`, so `Home.svelte` (and any
     * future page) just forwards its own `locale` prop straight through.
     *
     * `profile` (PR8c, optional) only feeds the nav's tagline
     * (`profile.specialty`, under the "Baubyte" wordmark) — it does not
     * widen this layout's responsibility beyond that single read, and every
     * consumer that predates PR8c (none exist yet outside `Home.svelte`)
     * keeps working with no `profile` at all: the tagline line simply
     * doesn't render.
     */
    let { children, locale = 'es', profile = null } = $props();
</script>

<div class="min-h-screen bg-base-100 text-base-content">
    <header class="navbar sticky top-0 z-50 border-b border-base-300 bg-base-100/80 px-4 backdrop-blur">
        <div class="flex-1">
            <a href="/" class="flex flex-col leading-tight">
                <span class="font-display text-lg font-bold text-base-content">Baubyte</span>
                {#if profile?.specialty}
                    <span class="font-display text-xs italic text-primary">{profile.specialty}</span>
                {/if}
            </a>
        </div>
        <div class="flex-none">
            <div class="flex items-center gap-1 rounded-full border border-base-300 bg-base-100/60 px-2 py-1">
                <LocaleSwitcher currentLocale={locale} />
            </div>
        </div>
    </header>

    <main>
        {@render children()}
    </main>
</div>
