import { describe, expect, test } from 'vitest';
import { renderMarkdown } from './markdown.js';

describe('renderMarkdown', () => {
    test('renders bold and italic text', () => {
        const result = renderMarkdown('Hola **Ada** y *Baubyte*');
        expect(result).toContain('<strong>Ada</strong>');
        expect(result).toContain('<em>Baubyte</em>');
    });

    test('renders code blocks and inline code', () => {
        const result = renderMarkdown('Probá `saludo()` o:\n```python\nprint("test")\n```');
        expect(result).toContain('<code>saludo()</code>');
        expect(result).toContain('<pre><code class="language-python">print("test")');
    });

    test('sanitizes script tags against XSS', () => {
        const malicious = 'Hola <script>alert("hack")</script>';
        const result = renderMarkdown(malicious);
        expect(result).not.toContain('<script>');
    });

    test('handles empty or non-string inputs gracefully', () => {
        expect(renderMarkdown('')).toBe('');
        expect(renderMarkdown(null)).toBe('');
        expect(renderMarkdown(undefined)).toBe('');
    });
});
