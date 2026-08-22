import { afterEach, beforeEach, describe, expect, test, vi } from 'vitest';

// `skillBarReveal` is a Svelte action applied to a single skill-bar fill
// element. The bar's own `style="width: {percentage}%"` (set in the
// component's markup) is always the correct final width — this action may
// only animate it in from 0% once it has confirmed it will genuinely run.
describe('skillBarReveal', () => {
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

    test('never zeroes the bar width when prefers-reduced-motion: reduce is active', async () => {
        window.matchMedia = vi.fn().mockImplementation((query) => ({
            matches: query === '(prefers-reduced-motion: reduce)',
        }));
        window.IntersectionObserver = class {
            observe() {}
            unobserve() {}
            disconnect() {}
        };

        const { skillBarReveal } = await import('./skillBarReveal.js');
        const node = document.createElement('div');
        node.style.width = '65%';

        const action = skillBarReveal(node, { percentage: 65 });

        expect(node.style.width).toBe('65%');

        action?.destroy?.();
    });

    test('never zeroes the bar width when IntersectionObserver is unavailable', async () => {
        window.matchMedia = vi.fn().mockReturnValue({ matches: false });
        delete window.IntersectionObserver;

        const { skillBarReveal } = await import('./skillBarReveal.js');
        const node = document.createElement('div');
        node.style.width = '80%';

        const action = skillBarReveal(node, { percentage: 80 });

        expect(node.style.width).toBe('80%');

        action?.destroy?.();
    });

    test('when animation is supported, zeroes then reveals the real width on intersection', async () => {
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

        const { skillBarReveal } = await import('./skillBarReveal.js');
        const node = document.createElement('div');
        node.style.width = '42%';

        const action = skillBarReveal(node, { percentage: 42 });

        expect(node.style.width).toBe('0%');

        expect(() => {
            observedCallback([{ isIntersecting: true, target: node }]);
        }).not.toThrow();

        // Deliberately NOT a one-shot reveal: must keep observing (never
        // unobserve) so scrolling away and back can re-trigger the fill.
        expect(unobserveCalled).toBe(false);

        action?.destroy?.();
    });

    test('resets to 0% on exit and can animate again on re-entry, instead of only filling once', async () => {
        window.matchMedia = vi.fn().mockReturnValue({ matches: false });

        let observedCallback;
        window.IntersectionObserver = class {
            constructor(callback) {
                observedCallback = callback;
            }
            observe() {}
            unobserve() {}
            disconnect() {}
        };

        const { skillBarReveal } = await import('./skillBarReveal.js');
        const node = document.createElement('div');
        node.style.width = '55%';

        const action = skillBarReveal(node, { percentage: 55 });

        observedCallback([{ isIntersecting: true, target: node }]);
        observedCallback([{ isIntersecting: false, target: node }]);
        expect(node.style.width).toBe('0%');

        expect(() => {
            observedCallback([{ isIntersecting: true, target: node }]);
        }).not.toThrow();

        action?.destroy?.();
    });
});
