import { animate } from 'animejs';
import { canAnimate } from './motion.js';

/**
 * Svelte action: `use:scrollReveal` fades a section in with an upward
 * translate EVERY time it enters the viewport (scrolling back up and down
 * re-triggers it — not a one-shot reveal). The owner explicitly asked for
 * this after finding the original "reveal once" behavior read as broken
 * ("las animaciones solo funciona una vez").
 *
 * The node's own markup/CSS is always the correct final state (visible,
 * `translateY(0)`). This action only ever adds a temporary hidden starting
 * style once `canAnimate()` confirms it will genuinely run the reveal
 * animation — so a JS failure (this action never mounting/running at all)
 * or `prefers-reduced-motion: reduce` both leave the section fully visible,
 * never stuck hidden. See `motion.js` for the shared guard.
 */
export function scrollReveal(node, options = {}) {
    const { distance = 48, duration = 800, delay = 0, threshold = 0.15 } = options;

    if (!canAnimate()) {
        return {};
    }

    // Start hidden so the very first reveal has something to animate from —
    // reset back to this same hidden state on exit (instant, unanimated) so
    // re-entering the viewport always has a real "from" state to tween from,
    // instead of flashing at full opacity for a frame before re-hiding.
    const hide = () => {
        node.style.opacity = '0';
        node.style.transform = `translateY(${distance}px) scale(0.97)`;
    };
    hide();

    let animating = false;

    const observer = new IntersectionObserver(
        (entries) => {
            for (const entry of entries) {
                if (entry.isIntersecting) {
                    if (animating) continue;
                    animating = true;
                    animate(node, {
                        opacity: [0, 1],
                        translateY: [distance, 0],
                        scale: [0.97, 1],
                        duration,
                        delay,
                        ease: 'outQuad',
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
