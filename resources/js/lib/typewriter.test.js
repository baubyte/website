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

        window.IntersectionObserver = class {
            observe() {}
            unobserve() {}
            disconnect() {}
        };

        const { typewriter } = await import('./typewriter.js');
        const node = document.createElement('h1');
        node.textContent = 'Martín Paredes';

        const action = typewriter(node);

        expect(node.style.clipPath).toBe('inset(0 100% 0 0)');
        expect(node.textContent).toBe('Martín Paredes');

        action?.destroy?.();
    });

    test('reveals once on entry and disconnects — one-shot, never re-hides on a later exit', async () => {
        window.matchMedia = vi.fn().mockReturnValue({ matches: false });

        let observedCallback;
        let disconnectCalled = false;
        window.IntersectionObserver = class {
            constructor(callback) {
                observedCallback = callback;
            }
            observe() {}
            unobserve() {}
            disconnect() {
                disconnectCalled = true;
            }
        };

        const { typewriter } = await import('./typewriter.js');
        const node = document.createElement('h1');
        node.textContent = 'Martín Paredes';

        const action = typewriter(node);

        expect(() => {
            observedCallback([{ isIntersecting: true, target: node }]);
        }).not.toThrow();

        // Real browsers stop calling back after disconnect(); this asserts
        // the action actually disconnects on its first reveal so a real
        // exit callback can never reach it again.
        expect(disconnectCalled).toBe(true);
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

    test('a late/spurious exit callback after disconnect cannot re-clip the text', async () => {
        vi.useFakeTimers();
        window.matchMedia = vi.fn().mockReturnValue({ matches: false });

        let observedCallback;
        let disconnected = false;
        window.IntersectionObserver = class {
            constructor(callback) {
                observedCallback = callback;
            }
            observe() {}
            unobserve() {}
            disconnect() {
                disconnected = true;
            }
        };

        const { typewriter } = await import('./typewriter.js');
        const node = document.createElement('h1');
        node.textContent = 'Martín Paredes';

        const action = typewriter(node, { duration: 900, delay: 0 });

        observedCallback([{ isIntersecting: true, target: node }]);
        expect(disconnected).toBe(true);

        // animejs drives onUpdate via requestAnimationFrame, which fake
        // timers don't advance, so what actually resolves clip-path here is
        // the safety net (a real setTimeout) reaching its own deadline —
        // exactly like the real browser does if the animation stalls.
        vi.advanceTimersByTime(900 + 0 + 1000 + 1);
        expect(node.style.clipPath).toBe('inset(0 0% 0 0)');

        // A real IntersectionObserver would never invoke this callback again
        // post-disconnect; the action's own logic only ever reveals on
        // `isIntersecting`, so even a stray false callback is a safe no-op.
        observedCallback([{ isIntersecting: false, target: node }]);

        expect(node.style.clipPath).toBe('inset(0 0% 0 0)');
        expect(node.textContent).toBe('Martín Paredes');

        action?.destroy?.();
        vi.useRealTimers();
    });
});
