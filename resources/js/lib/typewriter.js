import { animate, steps as stepsEase } from 'animejs';
import { canAnimate } from './motion.js';

// Animates clip-path over the real text (never per-character spans) so textContent stays untouched and accessible throughout.
export function typewriter(node, options = {}) {
    const { duration = 900, delay = 0, threshold = 0.5 } = options;

    if (!canAnimate()) {
        return {};
    }

    const charCount = node.textContent.trim().length || 1;

    const hide = () => {
        node.style.clipPath = 'inset(0 100% 0 0)';
    };
    const reveal = () => {
        node.style.clipPath = 'inset(0 0% 0 0)';
    };
    hide();

    let animating = false;
    let safetyTimer = null;
    const state = { p: 0 };

    const observer = new IntersectionObserver(
        (entries) => {
            for (const entry of entries) {
                if (entry.isIntersecting) {
                    if (animating) continue;
                    animating = true;
                    state.p = 0;
                    clearTimeout(safetyTimer);
                    // Never leave the real text permanently clipped if the animation stalls for any reason.
                    safetyTimer = setTimeout(reveal, duration + delay + 500);
                    animate(state, {
                        p: 100,
                        duration,
                        delay,
                        ease: stepsEase(charCount),
                        onUpdate: () => {
                            node.style.clipPath = `inset(0 ${100 - state.p}% 0 0)`;
                        },
                        onComplete: () => {
                            animating = false;
                            clearTimeout(safetyTimer);
                        },
                    });
                } else {
                    animating = false;
                    clearTimeout(safetyTimer);
                    hide();
                }
            }
        },
        { threshold },
    );

    observer.observe(node);

    return {
        destroy() {
            clearTimeout(safetyTimer);
            observer.disconnect();
        },
    };
}
