import { animate } from 'animejs';
import { canAnimate } from './motion.js';

/**
 * Svelte action for a single skill-bar fill element: `use:skillBarReveal={{
 * percentage }}`. The component's own markup sets
 * `style="width: {percentage}%"` directly — that is always the real, final
 * width. This action only ever temporarily zeroes it out and animates back
 * up to that same value once `canAnimate()` confirms it will genuinely run,
 * so a JS failure or `prefers-reduced-motion: reduce` both leave the bar at
 * its correct final width, never stuck at 0%. See `motion.js`.
 *
 * Re-animates EVERY time the bar re-enters the viewport (not a one-shot
 * reveal) — same fix applied to `scrollReveal.js` after the owner found the
 * one-shot behavior read as broken.
 */
export function skillBarReveal(node, options = {}) {
    const { percentage = 0, duration = 900, delay = 0, threshold = 0.3 } = options;

    if (!canAnimate()) {
        return {};
    }

    node.style.width = '0%';

    let animating = false;

    const observer = new IntersectionObserver(
        (entries) => {
            for (const entry of entries) {
                if (entry.isIntersecting) {
                    if (animating) continue;
                    animating = true;
                    animate(node, {
                        width: ['0%', `${percentage}%`],
                        duration,
                        delay,
                        ease: 'outQuad',
                        onComplete: () => {
                            animating = false;
                        },
                    });
                } else {
                    animating = false;
                    node.style.width = '0%';
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
