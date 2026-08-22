import '../css/app.css';
import './bootstrap';

import { createInertiaApp } from '@inertiajs/svelte';
import { resolvePageComponent } from 'laravel-vite-plugin/inertia-helpers';
import { mount } from 'svelte';

createInertiaApp({
    resolve: (name) =>
        resolvePageComponent(
            `./Pages/${name}.svelte`,
            import.meta.glob('./Pages/**/*.svelte'),
        ),
    setup({ el, App, props }) {
        mount(App, { target: el, props });
    },
});
