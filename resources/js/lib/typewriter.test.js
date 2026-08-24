import { afterEach, beforeEach, describe, expect, test, vi } from 'vitest';

describe('typewriter', () => {
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

    test('never clips the text when prefers-reduced-motion: reduce is active', async () => {
        window.matchMedia = vi.fn().mockImplementation((query) => ({
            matches: query === '(prefers-reduced-motion: reduce)',
        }));
        window.IntersectionObserver = class {
            observe() {}
            unobserve() {}
            disconnect() {}
        };

        const { typewriter } = await import('./typewriter.js');
        const node = document.createElement('h1');
        node.textContent = 'Martín Paredes';

        const action = typewriter(node);

        expect(node.style.clipPath).toBe('');
        expect(node.textContent).toBe('Martín Paredes');

        action?.destroy?.();
    });

    test('never clips the text when IntersectionObserver is unavailable', async () => {
        window.matchMedia = vi.fn().mockReturnValue({ matches: false });
        delete window.IntersectionObserver;

        const { typewriter } = await import('./typewriter.js');
        const node = document.createElement('h1');
        node.textContent = 'Martín Paredes';

        const action = typewriter(node);

        expect(node.style.clipPath).toBe('');
        expect(node.textContent).toBe('Martín Paredes');

        action?.destroy?.();
    });

    test('when animation is supported, clips the text before it enters the viewport', async () => {
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

        const { typewriter } = await import('./typewriter.js');
        const node = document.createElement('h1');
        node.textContent = 'Martín Paredes';

        const action = typewriter(node);

        expect(node.style.clipPath).toBe('inset(0 100% 0 0)');
        expect(node.textContent).toBe('Martín Paredes');

        expect(() => {
            observedCallback([{ isIntersecting: true, target: node }]);
        }).not.toThrow();

        // Deliberately NOT a one-shot reveal: must keep observing (never
        // unobserve) so scrolling away and back can re-trigger the wipe.
        expect(unobserveCalled).toBe(false);
        expect(node.textContent).toBe('Martín Paredes');

        action?.destroy?.();
    });

    test('re-clips on exit and can animate again on re-entry, instead of only wiping once', async () => {
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

        const { typewriter } = await import('./typewriter.js');
        const node = document.createElement('h1');
        node.textContent = 'Martín Paredes';

        const action = typewriter(node);

        observedCallback([{ isIntersecting: true, target: node }]);
        observedCallback([{ isIntersecting: false, target: node }]);
        expect(node.style.clipPath).toBe('inset(0 100% 0 0)');
        expect(node.textContent).toBe('Martín Paredes');

        expect(() => {
            observedCallback([{ isIntersecting: true, target: node }]);
        }).not.toThrow();
        expect(node.textContent).toBe('Martín Paredes');

        action?.destroy?.();
    });

    test('reveals the text on its own even if IntersectionObserver never calls back', async () => {
        vi.useFakeTimers();
        window.matchMedia = vi.fn().mockReturnValue({ matches: false });
        window.IntersectionObserver = class {
            observe() {}
            unobserve() {}
            disconnect() {}
        };

        const { typewriter } = await import('./typewriter.js');
        const node = document.createElement('h1');
        node.textContent = 'Martín Paredes';

        const action = typewriter(node, { duration: 900, delay: 0 });

        expect(node.style.clipPath).toBe('inset(0 100% 0 0)');

        vi.advanceTimersByTime(900 + 0 + 1000 + 1);

        expect(node.style.clipPath).toBe('inset(0 0% 0 0)');
        expect(node.textContent).toBe('Martín Paredes');

        action?.destroy?.();
        vi.useRealTimers();
    });
});
