import { animate, steps as stepsEase } from 'animejs';
import { canAnimate } from './motion.js';

// Animates clip-path over the real text (never per-character spans) so textContent stays untouched and accessible throughout.
// One-shot by design (unlike scrollReveal/skillBarReveal): a name/heading that already typed in should never re-hide itself
// on scroll-out, which is also what caused the reveal to flap in production when the observer re-fired.
export function typewriter(node, options = {}) {
    const { duration = 900, delay = 0, threshold = 0.5 } = options;

    if (!canAnimate()) {
        return {};
    }

    const charCount = node.textContent.trim().length || 1;

    let safetyTimer = null;
    const reveal = () => {
        clearTimeout(safetyTimer);
        node.style.clipPath = 'inset(0 0% 0 0)';
    };

    node.style.clipPath = 'inset(0 100% 0 0)';
    // Guarantees the real text becomes visible within a bounded time even if the observer never fires at all.
    safetyTimer = setTimeout(reveal, duration + delay + 1000);

    const observer = new IntersectionObserver(
        (entries) => {
            for (const entry of entries) {
                if (!entry.isIntersecting) {
                    continue;
                }

                observer.disconnect();
                clearTimeout(safetyTimer);
                safetyTimer = setTimeout(reveal, duration + delay + 1000);

                const state = { p: 0 };
                animate(state, {
                    p: 100,
                    duration,
                    delay,
                    ease: stepsEase(charCount),
                    onUpdate: () => {
                        node.style.clipPath = `inset(0 ${100 - state.p}% 0 0)`;
                    },
                    onComplete: () => {
                        clearTimeout(safetyTimer);
                    },
                });
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
