import '@testing-library/jest-dom/vitest';
import { afterEach, beforeEach } from 'vitest';
import { cleanup } from '@testing-library/svelte';
import { page } from '@inertiajs/svelte';

import esFront from '../lang/es/front.json';

beforeEach(() => {
    page.props = {
        locale: 'es',
        lang: {
            front: esFront,
        },
    };
});

// Global auto-cleanup between tests. Every pre-PR9 component test file only
// ever rendered once per file, so this gap was latent until
// `LocaleSwitcher.test.js` (PR9) rendered twice in the same file and
// `getByRole` started matching duplicated, un-unmounted nodes left in the
// jsdom document from the previous render.
afterEach(() => {
    cleanup();
});
