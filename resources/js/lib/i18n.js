import { lang } from '@erag/lang-sync-inertia/svelte';

const { __, trans, transChoice } = lang();

/**
 * Standard translation helper powered by @erag/lang-sync-inertia.
 * Automatically prefixes short group keys (e.g. 'about.title' -> 'front.about.title').
 *
 * @param {string} key
 * @param {Record<string, any>} [params]
 * @returns {string}
 */
export function t(key, params = {}) {
    const fullKey = key.includes('.') && !key.startsWith('front.') ? `front.${key}` : key;
    return __(fullKey, params);
}

export { __, trans, transChoice };
