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
 */
export function skillBarReveal(node, options = {}) {
    const { percentage = 0, duration = 900, delay = 0, threshold = 0.3 } = options;

    if (!canAnimate()) {
        return {};
    }

    node.style.width = '0%';

    const observer = new IntersectionObserver(
        (entries) => {
            for (const entry of entries) {
                if (!entry.isIntersecting) {
                    continue;
                }

                animate(node, {
                    width: ['0%', `${percentage}%`],
                    duration,
                    delay,
                    ease: 'outQuad',
                });

                observer.unobserve(entry.target);
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
