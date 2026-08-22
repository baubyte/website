<script>
    import ThemeToggle from '../Components/ThemeToggle.svelte';
    import LocaleSwitcher from '../Components/LocaleSwitcher.svelte';

    /**
     * `locale` is passed down as a plain prop (not read from the Inertia
     * `page` store here) so this layout stays trivially testable in
     * isolation. In the real app it's the same value shared globally by
     * `HandleInertiaRequests::share()` — Inertia merges shared props into
     * every page component's own `$props()`, so `Home.svelte` (and any
     * future page) just forwards its own `locale` prop straight through.
     */
    let { children, locale = 'es' } = $props();
</script>

<div class="min-h-screen bg-base-100 text-base-content">
    <header class="navbar sticky top-0 z-50 border-b border-base-300 bg-base-100/80 px-4 backdrop-blur">
        <div class="flex-1">
            <span class="font-display text-lg font-semibold">Baubyte</span>
        </div>
        <div class="flex-none gap-2">
            <LocaleSwitcher currentLocale={locale} />
            <ThemeToggle />
        </div>
    </header>

    <main>
        {@render children()}
    </main>
</div>
