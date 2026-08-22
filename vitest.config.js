import { defineConfig } from 'vitest/config';
import { svelte } from '@sveltejs/vite-plugin-svelte';

export default defineConfig({
    plugins: [svelte({ hot: false })],
    test: {
        environment: 'jsdom',
        setupFiles: ['./resources/js/tests/setup.js'],
        include: ['resources/js/**/*.test.js'],
    },
    resolve: {
        conditions: ['browser'],
    },
});
