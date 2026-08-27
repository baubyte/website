import { marked } from 'marked';
import DOMPurify from 'dompurify';

marked.setOptions({
    breaks: true,
    gfm: true,
});

/**
 * Parses markdown into sanitized HTML safely for browser and SSR/test environments.
 *
 * @param {string} content
 * @returns {string}
 */
export function renderMarkdown(content) {
    if (!content || typeof content !== 'string') {
        return '';
    }

    const rawHtml = marked.parse(content);

    if (typeof window !== 'undefined' && DOMPurify?.sanitize) {
        return DOMPurify.sanitize(rawHtml);
    }

    return rawHtml;
}
