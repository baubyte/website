import Typed from 'typed.js';
import { canAnimate } from './motion.js';

// Thin wrapper around Typed.js (battle-tested typing animation) that only starts once the node
// enters the viewport, one-shot, and falls back to the real final text if anything stalls.
export function typewriter(node, options = {}) {
    const { typeSpeed = 45, threshold = 0.5 } = options;

    console.log('[TW-DEBUG] mounted', { node, canAnimate: canAnimate() });

    if (!canAnimate()) {
        console.log('[TW-DEBUG] canAnimate() is false, bailing out');
        return {};
    }

    const text = node.textContent.trim();
    console.log('[TW-DEBUG] captured text at mount:', JSON.stringify(text));

    let typed = null;
    let safetyTimer = null;
    let started = false;

    const finish = () => {
        console.log('[TW-DEBUG] finish() called (safety net or onComplete cleanup)');
        clearTimeout(safetyTimer);
        typed?.destroy();
        node.textContent = text;
    };

    const start = () => {
        if (started) {
            console.log('[TW-DEBUG] start() called again, already started, ignoring');
            return;
        }
        started = true;
        console.log('[TW-DEBUG] start() running, about to construct Typed with text:', JSON.stringify(text));

        try {
            typed = new Typed(node, {
                strings: [text],
                typeSpeed,
                showCursor: false,
                onComplete: () => {
                    console.log('[TW-DEBUG] Typed onComplete fired');
                    clearTimeout(safetyTimer);
                },
            });
            console.log('[TW-DEBUG] new Typed() constructed successfully:', typed);
        } catch (err) {
            console.error('[TW-DEBUG] new Typed() THREW:', err);
        }

        // Never leave the real text stuck mid-type if Typed.js stalls for any reason.
        safetyTimer = setTimeout(finish, typeSpeed * text.length + 3000);
        console.log('[TW-DEBUG] safety timer armed for', typeSpeed * text.length + 3000, 'ms');
    };

    const observer = new IntersectionObserver(
        (entries) => {
            console.log(
                '[TW-DEBUG] observer callback fired',
                entries.map((e) => ({ isIntersecting: e.isIntersecting, ratio: e.intersectionRatio })),
            );
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
    console.log('[TW-DEBUG] observer.observe() called on node');

    return {
        destroy() {
            clearTimeout(safetyTimer);
            observer.disconnect();
            typed?.destroy();
        },
    };
}
