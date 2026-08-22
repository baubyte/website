<script>
    /**
     * Interactive dark-mode toggle. The blocking anti-flash script already
     * lives in `resources/views/app.blade.php` (PR7) and sets `data-theme`
     * on <html> synchronously before first paint — this component MUST NOT
     * duplicate that logic. It only reads the current theme in `onMount`
     * (never at module/instance init, since a future SSR render in PR10 has
     * no `document`/`localStorage`) and writes on user interaction.
     */
    import { onMount } from 'svelte';

    const LIGHT_THEME = 'baubyte-light';
    const DARK_THEME = 'baubyte-dark';

    let theme = $state(LIGHT_THEME);

    onMount(() => {
        theme = document.documentElement.dataset.theme === DARK_THEME
            ? DARK_THEME
            : LIGHT_THEME;
    });

    function toggleTheme() {
        theme = theme === DARK_THEME ? LIGHT_THEME : DARK_THEME;
        document.documentElement.dataset.theme = theme;
        localStorage.setItem('theme', theme);
    }
</script>

<button
    type="button"
    class="btn btn-ghost btn-circle"
    aria-label="Toggle dark mode"
    onclick={toggleTheme}
>
    {#if theme === DARK_THEME}
        <span aria-hidden="true">🌙</span>
    {:else}
        <span aria-hidden="true">☀️</span>
    {/if}
</button>
