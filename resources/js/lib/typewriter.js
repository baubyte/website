import { animate } from 'animejs';
import { canAnimate } from './motion.js';

// Animates clip-path over the real text (never per-character spans) so textContent stays untouched and accessible throughout.
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
