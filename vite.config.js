import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';
import { svelte } from '@sveltejs/vite-plugin-svelte';

export default defineConfig({
    plugins: [
        laravel({
            input: 'resources/js/app.js',
            // PR10: SSR entry, built separately via `vite build --ssr`
            // (see package.json's `build` script). Output lands at
            // `bootstrap/ssr/ssr.js` (laravel-vite-plugin's default
            // `ssrOutputDirectory`), which is also where Inertia's
            // `BundleDetector` looks for it automatically.
            ssr: 'resources/js/ssr.js',
            refresh: true,
        }),
        svelte(),
    ],
});
