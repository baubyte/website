import { afterEach, beforeEach, describe, expect, test, vi } from 'vitest';

// `scrollReveal` is a Svelte action (see `Components/*.svelte`) that fades
// + translates a section into view the first time it enters the viewport.
// These tests only exercise the action function directly (no component
// rendering needed) — the contract under test is purely: "does this ever
// leave content hidden when it shouldn't?"
describe('scrollReveal', () => {
    let originalIntersectionObserver;
    let originalMatchMedia;

    beforeEach(() => {
        originalIntersectionObserver = window.IntersectionObserver;
        originalMatchMedia = window.matchMedia;
    });

    afterEach(() => {
        if (originalIntersectionObserver === undefined) {
            delete window.IntersectionObserver;
        } else {
            window.IntersectionObserver = originalIntersectionObserver;
        }
        window.matchMedia = originalMatchMedia;
        vi.resetModules();
    });

    test('never hides the node when prefers-reduced-motion: reduce is active', async () => {
        window.matchMedia = vi.fn().mockImplementation((query) => ({
            matches: query === '(prefers-reduced-motion: reduce)',
            media: query,
        }));
        window.IntersectionObserver = class {
            observe() {}
            unobserve() {}
            disconnect() {}
        };

        const { scrollReveal } = await import('./scrollReveal.js');
        const node = document.createElement('section');
        node.textContent = 'About content';

        const action = scrollReveal(node);

        // The node must stay in its default, fully visible state — no
        // inline opacity/transform override was ever applied.
        expect(node.style.opacity).toBe('');
        expect(node.style.transform).toBe('');

        action?.destroy?.();
    });

    test('never hides the node when IntersectionObserver is unavailable (JS/runtime limitation)', async () => {
        window.matchMedia = vi.fn().mockReturnValue({ matches: false });
        delete window.IntersectionObserver;

        const { scrollReveal } = await import('./scrollReveal.js');
        const node = document.createElement('section');
        node.textContent = 'About content';

        const action = scrollReveal(node);

        expect(node.style.opacity).toBe('');
        expect(node.style.transform).toBe('');

        action?.destroy?.();
    });

    test('when animation is supported, sets a hidden starting state and reveals on intersection without throwing', async () => {
        window.matchMedia = vi.fn().mockReturnValue({ matches: false });

        let observedCallback;
        let unobserveCalled = false;
        window.IntersectionObserver = class {
            constructor(callback) {
                observedCallback = callback;
            }
            observe() {}
            unobserve() {
                unobserveCalled = true;
            }
            disconnect() {}
        };

        const { scrollReveal } = await import('./scrollReveal.js');
        const node = document.createElement('section');

        const action = scrollReveal(node);

        // Only once we know we WILL animate do we set the temporary hidden
        // starting style.
        expect(node.style.opacity).toBe('0');

        expect(() => {
            observedCallback([{ isIntersecting: true, target: node }]);
        }).not.toThrow();

        expect(unobserveCalled).toBe(true);

        action?.destroy?.();
    });

    test('returns no-op handlers when called with no window/DOM capability at all', async () => {
        window.matchMedia = vi.fn().mockReturnValue({ matches: false });
        delete window.IntersectionObserver;

        const { scrollReveal } = await import('./scrollReveal.js');
        const node = document.createElement('section');

        const action = scrollReveal(node);

        expect(action).toEqual({});
    });
});
