import Atropos from 'atropos';
import { canAnimate } from './motion.js';

/**
 * Svelte action: `use:tilt` turns a node into a mouse-move 3D tilt/parallax
 * card via Atropos — the exact library gustavomorinaga.dev uses for its
 * hero photo frame (confirmed by reading its real source, not guessed).
 * Atropos is DOM/CSS-transform based, not WebGL/Three.js — no SSR conflict.
 *
 * Atropos requires this exact nested markup inside the node (it queries for
 * these classes rather than creating them):
 *   .atropos > .atropos-scale > .atropos-rotate > .atropos-inner
 *
 * Respects `prefers-reduced-motion`/no-IntersectionObserver via the shared
 * `canAnimate()` guard (same contract as `scrollReveal`): when animation
 * shouldn't run, this is a no-op and the frame stays static, never broken.
 */
export function tilt(node, options = {}) {
    if (!canAnimate()) {
        return {};
    }

    const instance = Atropos({
        el: node,
        shadow: false,
        highlight: false,
        rotateXMax: 12,
        rotateYMax: 12,
        duration: 300,
        ...options,
    });

    return {
        destroy() {
            instance?.destroy();
        },
    };
}
