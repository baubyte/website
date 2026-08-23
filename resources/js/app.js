import '../css/app.css';
import './bootstrap';

import { createInertiaApp } from '@inertiajs/svelte';
import { resolvePageComponent } from 'laravel-vite-plugin/inertia-helpers';
import { mount } from 'svelte';

import esFront from './lang/es/front.json';
import enFront from './lang/en/front.json';

const staticLangs = {
    es: { front: esFront },
    en: { front: enFront },
};

createInertiaApp({
    resolve: (name) =>
        resolvePageComponent(
            `./Pages/${name}.svelte`,
            import.meta.glob('./Pages/**/*.svelte'),
        ),
    setup({ el, App, props }) {
        // Inject static JSONs directly into the Inertia payload on boot
        // so @erag/lang-sync-inertia picks it up natively without a backend payload
        const locale = props.initialPage.props.locale || 'es';
        props.initialPage.props.lang = staticLangs[locale];

        mount(App, { target: el, props });

        // Removes the plain-HTML/CSS pre-hydration loader from
        // app.blade.php now that Svelte has actually mounted real content
        // in its place. There's no Inertia-router-driven page-transition
        // loader in this app: the one client-side "navigation" (the locale
        // switch) is a deliberate full browser reload, not an Inertia
        // visit, so `router.on('start'/'finish')` never fires for it.
        document.getElementById('pre-hydration-loader')?.remove();
    },
});
