import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';
import { svelte } from '@sveltejs/vite-plugin-svelte';

const port = 5173;
const origin = `${process.env.DDEV_PRIMARY_URL}:${port}`;
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
    server: {
        // respond to all network requests
        host: '0.0.0.0',
        port: port,
        strictPort: true,
        // Defines the origin of the generated asset URLs during development,
        // this will also be used for the public/hot file (devserver URL)
        origin: origin,
        cors: {
            origin: /https?:\/\/([a-zA-Z0-9-.]+)?\.ddev\.site(:\d+)?$/,
        },
    }
});
