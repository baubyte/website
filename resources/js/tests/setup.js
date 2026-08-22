import '@testing-library/jest-dom/vitest';
import { afterEach } from 'vitest';
import { cleanup } from '@testing-library/svelte';

// Global auto-cleanup between tests. Every pre-PR9 component test file only
// ever rendered once per file, so this gap was latent until
// `LocaleSwitcher.test.js` (PR9) rendered twice in the same file and
// `getByRole` started matching duplicated, un-unmounted nodes left in the
// jsdom document from the previous render.
afterEach(() => {
    cleanup();
});
