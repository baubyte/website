import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';
import { svelte, vitePreprocess } from '@sveltejs/vite-plugin-svelte';

export default defineConfig({
    plugins: [
        laravel({
            input: 'resources/js/app.js',
            refresh: true,
        }),
        // `vitePreprocess()` is what actually runs `sass` on `<style
        // lang="scss">` blocks (CubeLoader.svelte, ported directly from the
        // reference site) -- installing the `sass` package alone isn't
        // enough without this.
        svelte({ preprocess: vitePreprocess() }),
    ],
});
