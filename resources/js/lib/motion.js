/**
 * Shared accessibility/capability guard for every scroll-triggered
 * animation in this app (see `scrollReveal.js`, `skillBarReveal.js`).
 *
 * Animation is enabled ONLY when both are true:
 *   - the runtime actually supports `IntersectionObserver` (an old browser,
 *     a broken polyfill, or this module simply never running must never
 *     crash the page — it just means no animation happens), and
 *   - the user has NOT requested `prefers-reduced-motion: reduce`. This is
 *     a real accessibility requirement, not a nice-to-have.
 *
 * Every scroll-reveal action in this app follows the same contract: the
 * component's own markup (what a no-JS or SSR render produces, see PR10) is
 * ALWAYS the final, fully visible/correct state. An action is only allowed
 * to set a temporary "hidden"/"zeroed" starting style once it has confirmed
 * — via this function — that it will actually animate back to that same
 * final state. This means a JS failure (this module never running at all)
 * can never leave content invisible or a bar stuck at 0%.
 */
export function canAnimate() {
    if (typeof window === 'undefined') {
        return false;
    }

    if (typeof IntersectionObserver === 'undefined') {
        return false;
    }

    if (
        typeof window.matchMedia === 'function' &&
        window.matchMedia('(prefers-reduced-motion: reduce)').matches
    ) {
        return false;
    }

    return true;
}
