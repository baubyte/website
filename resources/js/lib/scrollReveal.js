import { animate } from 'animejs';
import { canAnimate } from './motion.js';

/**
 * Svelte action: `use:scrollReveal` on a section fades it in with a subtle
 * upward translate the first time it enters the viewport.
 *
 * The node's own markup/CSS is always the correct final state (visible,
 * `translateY(0)`). This action only ever adds a temporary hidden starting
 * style once `canAnimate()` confirms it will genuinely run the reveal
 * animation — so a JS failure (this action never mounting/running at all)
 * or `prefers-reduced-motion: reduce` both leave the section fully visible,
 * never stuck hidden. See `motion.js` for the shared guard.
 */
export function scrollReveal(node, options = {}) {
    const { distance = 24, duration = 700, delay = 0, threshold = 0.15 } = options;

    if (!canAnimate()) {
        return {};
    }

    node.style.opacity = '0';
    node.style.transform = `translateY(${distance}px)`;

    const observer = new IntersectionObserver(
        (entries) => {
            for (const entry of entries) {
                if (!entry.isIntersecting) {
                    continue;
                }

                animate(node, {
                    opacity: [0, 1],
                    translateY: [distance, 0],
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
