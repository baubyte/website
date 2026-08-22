<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        {{--
            Display/body typography (PR8b): Fraunces (display/titles) +
            Public Sans (body), served from Google Fonts' CDN with
            `preconnect` rather than self-hosted woff2 files — self-hosting
            via the Vite pipeline was considered but dropped for this unit
            to prioritize finishing the full visual redesign over perfect
            font delivery; `tailwind.config.js` maps `font-display`/
            `font-sans` to these exact family names.
        --}}
        <link rel="preconnect" href="https://fonts.googleapis.com">
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
        <link
            rel="stylesheet"
            href="https://fonts.googleapis.com/css2?family=Fraunces:opsz,wght@9..144,300..900&family=Public+Sans:wght@400;500;600;700&display=swap"
        >

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
