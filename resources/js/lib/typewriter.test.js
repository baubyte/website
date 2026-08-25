import { afterEach, beforeEach, describe, expect, test, vi } from 'vitest';

describe('typewriter', () => {
    let originalIntersectionObserver;
    let originalMatchMedia;

    beforeEach(() => {
        originalIntersectionObserver = window.IntersectionObserver;
        originalMatchMedia = window.matchMedia;
        vi.resetModules();
    });

    afterEach(() => {
        if (originalIntersectionObserver === undefined) {
            delete window.IntersectionObserver;
        } else {
            window.IntersectionObserver = originalIntersectionObserver;
        }
        window.matchMedia = originalMatchMedia;
        vi.doUnmock('typed.js');
        vi.useRealTimers();
    });

    test('never touches the text when prefers-reduced-motion: reduce is active', async () => {
        window.matchMedia = vi.fn().mockImplementation((query) => ({
            matches: query === '(prefers-reduced-motion: reduce)',
        }));
        window.IntersectionObserver = class {
            observe() {}
            disconnect() {}
        };

        const typedCtor = vi.fn();
        vi.doMock('typed.js', () => ({ default: typedCtor }));

        const { typewriter } = await import('./typewriter.js');
        const node = document.createElement('h1');
        node.textContent = 'Martín Paredes';

        const action = typewriter(node);

        expect(typedCtor).not.toHaveBeenCalled();
        expect(node.textContent).toBe('Martín Paredes');

        action?.destroy?.();
    });

    test('never touches the text when IntersectionObserver is unavailable', async () => {
        window.matchMedia = vi.fn().mockReturnValue({ matches: false });
        delete window.IntersectionObserver;

        const typedCtor = vi.fn();
        vi.doMock('typed.js', () => ({ default: typedCtor }));

        const { typewriter } = await import('./typewriter.js');
        const node = document.createElement('h1');
        node.textContent = 'Martín Paredes';

        const action = typewriter(node);

        expect(typedCtor).not.toHaveBeenCalled();
        expect(node.textContent).toBe('Martín Paredes');

        action?.destroy?.();
    });

    test('starts Typed.js once the node enters the viewport, then disconnects (one-shot)', async () => {
        window.matchMedia = vi.fn().mockReturnValue({ matches: false });

        let observedCallback;
        let disconnectCalled = false;
        window.IntersectionObserver = class {
            constructor(callback) {
                observedCallback = callback;
            }
            observe() {}
            disconnect() {
                disconnectCalled = true;
            }
        };

        const destroyMock = vi.fn();
        function MockTyped() { return { destroy: destroyMock }; }
        const typedCtor = vi.fn(MockTyped);
        vi.doMock('typed.js', () => ({ default: typedCtor }));

        const { typewriter } = await import('./typewriter.js');
        const node = document.createElement('h1');
        node.textContent = 'Martín Paredes';

        const action = typewriter(node);

        expect(typedCtor).not.toHaveBeenCalled();

        observedCallback([{ isIntersecting: true, target: node }]);

        expect(typedCtor).toHaveBeenCalledTimes(1);
        expect(typedCtor.mock.calls[0][0]).toBe(node);
        expect(typedCtor.mock.calls[0][1].strings).toEqual(['Martín Paredes']);
        expect(disconnectCalled).toBe(true);

        // A second intersection callback (real observer would never send one
        // post-disconnect, but guard against it anyway) must not re-init.
        observedCallback([{ isIntersecting: true, target: node }]);
        expect(typedCtor).toHaveBeenCalledTimes(1);

        action?.destroy?.();
    });

    test('falls back to the real final text if Typed.js never calls onComplete', async () => {
        vi.useFakeTimers();
        window.matchMedia = vi.fn().mockReturnValue({ matches: false });

        let observedCallback;
        window.IntersectionObserver = class {
            constructor(callback) {
                observedCallback = callback;
            }
            observe() {}
            disconnect() {}
        };

        const destroyMock = vi.fn();
        function MockTyped() { return { destroy: destroyMock }; }
        const typedCtor = vi.fn(MockTyped);
        vi.doMock('typed.js', () => ({ default: typedCtor }));

        const { typewriter } = await import('./typewriter.js');
        const node = document.createElement('h1');
        node.textContent = 'Martín Paredes';

        const action = typewriter(node, { typeSpeed: 45 });

        observedCallback([{ isIntersecting: true, target: node }]);
        // Typed.js's mock never called onComplete, so only the safety net can restore the text.
        node.textContent = '';

        vi.advanceTimersByTime(45 * 'Martín Paredes'.length + 3000 + 1);

        expect(destroyMock).toHaveBeenCalledTimes(1);
        expect(node.textContent).toBe('Martín Paredes');

        action?.destroy?.();
    });

    test('destroy() tears down the timer, observer, and any live Typed.js instance', async () => {
        window.matchMedia = vi.fn().mockReturnValue({ matches: false });

        let observedCallback;
        let disconnectCalledTimes = 0;
        window.IntersectionObserver = class {
            constructor(callback) {
                observedCallback = callback;
            }
            observe() {}
            disconnect() {
                disconnectCalledTimes += 1;
            }
        };

        const destroyMock = vi.fn();
        function MockTyped() { return { destroy: destroyMock }; }
        const typedCtor = vi.fn(MockTyped);
        vi.doMock('typed.js', () => ({ default: typedCtor }));

        const { typewriter } = await import('./typewriter.js');
        const node = document.createElement('h1');
        node.textContent = 'Martín Paredes';

        const action = typewriter(node);
        observedCallback([{ isIntersecting: true, target: node }]);

        action?.destroy?.();

        expect(destroyMock).toHaveBeenCalledTimes(1);
        // Already disconnected on entry (one-shot); destroy() calling it again is a harmless no-op.
        expect(disconnectCalledTimes).toBeGreaterThanOrEqual(1);
    });
});
