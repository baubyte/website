import { animate } from 'animejs';
import { canAnimate } from './motion.js';

/**
 * Svelte action: `use:typewriter` reveals the node's own text with a
 * typewriter-style wipe every time it enters the viewport (not a one-shot
 * reveal — same re-trigger contract as `scrollReveal.js`/`skillBarReveal.js`
 * after the owner found one-shot reveals reading as broken).
 *
 * Deliberately does NOT split the text into per-character spans (that fights
 * Svelte 5's ownership of text nodes when reactive props update, and would
 * need an ARIA shim to undo the visual character soup for assistive tech).
 * Instead this animates a plain JS scalar (`state.p`, 0→100) via animejs and
 * writes it out as `clip-path: inset(0 {100-p}% 0 0)` on every tick, wiping
 * the already-correct text into view left-to-right with `ease: 'steps(n)'`
 * (n = character count) for the classic typewriter cadence. The node's own
 * `textContent` is never touched, so the real, final, accessible text is in
 * the DOM the entire time — no separate hidden duplicate, no `aria-live`
 * needed.
 *
 * Only ever applies a temporary fully-clipped starting state once
 * `canAnimate()` confirms it will genuinely run — a JS failure (this action
 * never mounting/running at all) or `prefers-reduced-motion: reduce` both
 * leave the text fully visible, never stuck clipped. See `motion.js`.
 */
export function typewriter(node, options = {}) {
    const { duration = 900, delay = 0, threshold = 0.5 } = options;

    if (!canAnimate()) {
        return {};
    }

    const steps = node.textContent.trim().length || 1;

    const hide = () => {
        node.style.clipPath = 'inset(0 100% 0 0)';
    };
    hide();

    let animating = false;
    const state = { p: 0 };

    const observer = new IntersectionObserver(
        (entries) => {
            for (const entry of entries) {
                if (entry.isIntersecting) {
                    if (animating) continue;
                    animating = true;
                    state.p = 0;
                    animate(state, {
                        p: 100,
                        duration,
                        delay,
                        ease: `steps(${steps})`,
                        onUpdate: () => {
                            node.style.clipPath = `inset(0 ${100 - state.p}% 0 0)`;
                        },
                        onComplete: () => {
                            animating = false;
                        },
                    });
                } else {
                    animating = false;
                    hide();
                }
            }
        },
        { threshold },
    );

    observer.observe(node);

    return {
        destroy() {
            observer.disconnect();
        },
    };
}
