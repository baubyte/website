<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        {{--
            Blocking dark-mode script. Must run before anything else in
            <head> so `data-theme` is set on <html> before first paint,
            avoiding a light-mode flash. Reads `localStorage['theme']`
            ('baubyte-light'|'baubyte-dark' — the DaisyUI theme names
            configured in tailwind.config.js); falls back to the OS
            preference via `prefers-color-scheme`. `ThemeToggle.svelte`
            (PR8) reads/writes these exact same values in `onMount`, never
            duplicating this pre-paint logic.
        --}}
        <script>
            (function () {
                try {
                    var stored = localStorage.getItem('theme');
                    var theme = (stored === 'baubyte-light' || stored === 'baubyte-dark')
                        ? stored
                        : (window.matchMedia && window.matchMedia('(prefers-color-scheme: dark)').matches ? 'baubyte-dark' : 'baubyte-light');
                    document.documentElement.setAttribute('data-theme', theme);
                } catch (e) {}
            })();
        </script>

        @include('partials.seo', ['seoPageKey' => $seoPageKey ?? 'home'])

        @vite('resources/js/app.js')
        @inertiaHead
    </head>
    <body>
        @inertia
    </body>
</html>
