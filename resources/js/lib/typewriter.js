import Typed from 'typed.js';
import { canAnimate } from './motion.js';

// Thin wrapper around Typed.js (battle-tested typing animation) that only starts once the node
// enters the viewport, one-shot, and falls back to the real final text if anything stalls.
export function typewriter(node, options = {}) {
    const { typeSpeed = 45, threshold = 0.5 } = options;

    if (!canAnimate()) {
        return {};
    }

    const text = node.textContent.trim();

    let typed = null;
    let safetyTimer = null;
    let started = false;

    const finish = () => {
        clearTimeout(safetyTimer);
        typed?.destroy();
        node.textContent = text;
    };

    const start = () => {
        if (started) {
            return;
        }
        started = true;

        // The node's SSR/no-JS markup already has the real text in it. Typed.js
        // backspaces whatever is already there before typing, so without this
        // it silently backspaces-then-retypes the identical string, which
        // reads as "nothing happens" instead of an actual typing effect.
        node.textContent = '';

        typed = new Typed(node, {
            strings: [text],
            typeSpeed,
            showCursor: false,
            onComplete: () => clearTimeout(safetyTimer),
        });

        // Never leave the real text stuck mid-type if Typed.js stalls for any reason.
        safetyTimer = setTimeout(finish, typeSpeed * text.length + 3000);
    };

    const observer = new IntersectionObserver(
        (entries) => {
            for (const entry of entries) {
                if (entry.isIntersecting) {
                    observer.disconnect();
                    start();
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
            typed?.destroy();
        },
    };
}
