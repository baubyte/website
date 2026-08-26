<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" data-theme="baubyte-dark">
    <head>
        <!-- Favicons -->
        <link href="{{ asset('favicon.png') }}" rel="icon">
        <link href="{{ asset('apple-touch-icon.png') }}" rel="apple-touch-icon">
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        {{--
            Display/body typography: Orbitron (display/titles) + Fira Code
            (code-style eyebrow) + Public Sans (body) — Orbitron and Fira
            Code are the SAME fonts gustavomorinaga.dev uses (confirmed
            against its real served HTML), recolored to this project's own
            sage palette instead of copying its neon colors. Served from
            Google Fonts' CDN with `preconnect` rather than self-hosted
            woff2 files (same tradeoff as before); `tailwind.config.js` maps
            `font-display`/`font-mono`/`font-sans` to these exact families.
        --}}
        <link rel="preconnect" href="https://fonts.googleapis.com">
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
        <link
            rel="stylesheet"
            href="https://fonts.googleapis.com/css2?family=Fira+Code:wght@400;500;600&family=Orbitron:wght@400..900&family=Public+Sans:wght@400;500;600;700&display=swap"
        >

        @include('partials.seo', ['seoPageKey' => $seoPageKey ?? 'home'])

        {{--
            Pre-hydration loader: covers the real, verified gap between this
            HTML arriving and Svelte actually mounting into #app (confirmed
            earlier by screenshotting the page before that point — a blank
            themed background, no content). Plain server-rendered HTML/CSS
            on purpose, since Svelte itself hasn't loaded yet at this point
            in the page lifecycle — it can't cover its own loading gap.

            Same 6-face CSS 3D cube as `Components/CubeLoader.svelte`
            (ported from gustavomorinaga.dev's real source), sized to its
            "lg" preset (80px box, 40px half-size, 4px border) since this
            one spot doesn't need the size prop. `border-primary`/
            `bg-primary/20` are real Tailwind classes picked up because
            Tailwind scans `.blade.php` files too — the theme's actual
            accent color, not a hardcoded one.

            `app.js` removes #pre-hydration-loader immediately after
            `mount(App, ...)` returns.
        --}}
        <style>
            #pre-hydration-loader {
                position: fixed;
                inset: 0;
                z-index: 60;
                display: flex;
                align-items: center;
                justify-content: center;
            }
            #pre-hydration-loader .cube {
                position: relative;
                width: 80px;
                height: 80px;
                animation: pre-hydration-spin 2s infinite ease;
                transform-style: preserve-3d;
            }
            #pre-hydration-loader .cube > div {
                position: absolute;
                width: 100%;
                height: 100%;
                border-style: solid;
                border-width: 4px;
            }
            #pre-hydration-loader .cube > div:nth-of-type(1) { transform: translateZ(-40px) rotateY(180deg); }
            #pre-hydration-loader .cube > div:nth-of-type(2) { transform: rotateY(-270deg) translateX(50%); transform-origin: top right; }
            #pre-hydration-loader .cube > div:nth-of-type(3) { transform: rotateY(270deg) translateX(-50%); transform-origin: center left; }
            #pre-hydration-loader .cube > div:nth-of-type(4) { transform: rotateX(90deg) translateY(-50%); transform-origin: top center; }
            #pre-hydration-loader .cube > div:nth-of-type(5) { transform: rotateX(-90deg) translateY(50%); transform-origin: bottom center; }
            #pre-hydration-loader .cube > div:nth-of-type(6) { transform: translateZ(40px); }
            @keyframes pre-hydration-spin {
                0% { transform: rotate(45deg) rotateX(-25deg) rotateY(25deg); }
                50% { transform: rotate(45deg) rotateX(-385deg) rotateY(25deg); }
                100% { transform: rotate(45deg) rotateX(-385deg) rotateY(385deg); }
            }
            
            /* If SSR generated content, hide the loader instantly so we don't cover the HTML */
            #app[data-server-rendered="true"] ~ #pre-hydration-loader {
                display: none !important;
            }
        </style>
        
        <noscript>
            <style>
                #pre-hydration-loader { display: none !important; }
            </style>
        </noscript>

        @routes
        @vite('resources/js/app.js')
        @inertiaHead
    </head>
    <body>
        @inertia

        <div id="pre-hydration-loader" class="bg-base-100" role="status" aria-label="Loading">
            <div class="cube">
                <div class="border-primary bg-primary/20"></div>
                <div class="border-primary bg-primary/20"></div>
                <div class="border-primary bg-primary/20"></div>
                <div class="border-primary bg-primary/20"></div>
                <div class="border-primary bg-primary/20"></div>
                <div class="border-primary bg-primary/20"></div>
            </div>
        </div>
        {{--
            Safety net, independent of app.js: if the JS bundle ever fails
            to load/execute for any reason, app.js's own removal
            (`document.getElementById('pre-hydration-loader')?.remove()`)
            never runs and this would otherwise sit on screen forever. Force
            it gone after 5s regardless — by then either the real page
            already mounted over it, or something is broken and a stuck
            loader is strictly worse than an unstyled page.
        --}}
        <script>
            setTimeout(function () {
                var el = document.getElementById('pre-hydration-loader');
                if (el) el.remove();
            }, 5000);
        </script>
    </body>
</html>
