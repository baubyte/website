import { createInertiaApp } from '@inertiajs/svelte';
import createServer from '@inertiajs/svelte/server';
import { resolvePageComponent } from 'laravel-vite-plugin/inertia-helpers';
import { render } from 'svelte/server';

// Inertia's official Node SSR entry (D2/D9 of the migration design doc).
// Bundled by `vite build --ssr` (see vite.config.js's `ssr` input) into
// `bootstrap/ssr/ssr.js`, then run as `node bootstrap/ssr/ssr.js` by the
// `ssr` Docker service. Port 13714 is Inertia's own SSR default and is
// what `config/inertia.php`'s `ssr.url` (`http://ssr:13714` in
// docker-compose) points at.
//
// No CSS import here on purpose: Tailwind/DaisyUI styling is irrelevant to
// server-rendered markup and pulling it into the SSR bundle would just add
// dead weight to a process that never serves a stylesheet.
createServer((page) =>
    createInertiaApp({
        page,
        resolve: (name) =>
            resolvePageComponent(
                `./Pages/${name}.svelte`,
                import.meta.glob('./Pages/**/*.svelte'),
            ),
        setup({ App, props }) {
            // Mirrors app.js's mount() call, but svelte/server's render()
            // is the SSR equivalent: it returns { head, body } strings
            // instead of mounting into a real DOM element (there is none
            // in Node). createInertiaApp wraps this in Inertia's own
            // head/body envelope before handing it back to createServer.
            return render(App, { props });
        },
    }),
);
