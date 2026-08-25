import Typed from 'typed.js';
import { canAnimate } from './motion.js';

// Thin wrapper around Typed.js (battle-tested typing animation) that only starts once the node
// enters the viewport, one-shot, and falls back to the real final text if anything stalls.
//
// `revealDelay`: when this heading sits inside a `use:scrollReveal` section, that section starts
// at `opacity: 0` and only fades in once its own (separate) IntersectionObserver fires. Without a
// delay here, this action's typing can run — and finish — while the section is still invisible,
// so the effect is never actually seen. Pass a `revealDelay` matching the reveal's fade duration
// (and a matching `threshold`, e.g. `scrollReveal`'s default 0.15) so typing starts right as the
// section becomes visible instead of racing it.
export function typewriter(node, options = {}) {
    const { typeSpeed = 45, threshold = 0.5, showCursor = false, revealDelay = 0 } = options;

    if (!canAnimate()) {
        return {};
    }

    const text = node.textContent.trim();

    let typed = null;
    let safetyTimer = null;
    let revealTimer = null;
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
            showCursor,
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
                    revealTimer = setTimeout(start, revealDelay);
                }
            }
        },
        { threshold },
    );

    observer.observe(node);

    return {
        destroy() {
            clearTimeout(safetyTimer);
            clearTimeout(revealTimer);
            observer.disconnect();
            typed?.destroy();
        },
    };
}
