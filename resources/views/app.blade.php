<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        {{--
            Blocking dark-mode script. Must run before anything else in
            <head> so `data-theme` is set on <html> before first paint,
            avoiding a light-mode flash. Reads `localStorage['theme']`
            ('light'|'dark'); falls back to the OS preference via
            `prefers-color-scheme`. The interactive toggle UI is added in
            PR8 alongside the Svelte components; this script only owns the
            initial, pre-paint state.
        --}}
        <script>
            (function () {
                try {
                    var stored = localStorage.getItem('theme');
                    var theme = (stored === 'light' || stored === 'dark')
                        ? stored
                        : (window.matchMedia && window.matchMedia('(prefers-color-scheme: dark)').matches ? 'dark' : 'light');
                    document.documentElement.setAttribute('data-theme', theme);
                } catch (e) {}
            })();
        </script>

        @include('partials.seo', ['seoPageKey' => $seoPageKey ?? 'home'])

        @vite(['resources/css/app.css', 'resources/js/app.js'])
        @inertiaHead
    </head>
    <body>
        @inertia
    </body>
</html>
