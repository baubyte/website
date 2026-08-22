import { readFileSync } from 'node:fs';
import { join } from 'node:path';
import { afterEach, beforeEach, describe, expect, test } from 'vitest';
import { render, screen, fireEvent } from '@testing-library/svelte';
import ThemeToggle from './ThemeToggle.svelte';

const componentPath = join(process.cwd(), 'resources/js/Components/ThemeToggle.svelte');

describe('ThemeToggle', () => {
    beforeEach(() => {
        document.documentElement.removeAttribute('data-theme');
        localStorage.clear();
    });

    afterEach(() => {
        document.documentElement.removeAttribute('data-theme');
        localStorage.clear();
    });

    test('toggles data-theme on <html> and persists it in localStorage on click', async () => {
        document.documentElement.setAttribute('data-theme', 'baubyte-light');

        render(ThemeToggle);

        const button = screen.getByRole('button');
        await fireEvent.click(button);

        expect(document.documentElement.getAttribute('data-theme')).toBe('baubyte-dark');
        expect(localStorage.getItem('theme')).toBe('baubyte-dark');

        await fireEvent.click(button);

        expect(document.documentElement.getAttribute('data-theme')).toBe('baubyte-light');
        expect(localStorage.getItem('theme')).toBe('baubyte-light');
    });

    test('never reads document/localStorage in the component script outside onMount/event handlers', () => {
        const source = readFileSync(componentPath, 'utf-8');
        const scriptMatch = source.match(/<script[^>]*>([\s\S]*?)<\/script>/);
        expect(scriptMatch).not.toBeNull();

        const scriptBody = scriptMatch[1];

        // Strip the bodies of onMount(...) and any function declarations/
        // expressions (event handlers) — those are allowed to touch
        // document/localStorage. What must stay clean is the top-level
        // (module/instance init) code that runs before onMount fires.
        const withoutOnMount = scriptBody.replace(/onMount\(\s*(?:\(\)\s*=>|function\s*\([^)]*\))\s*\{[\s\S]*?\n\s*\}\s*\)\s*;?/, '');
        const withoutFunctions = withoutOnMount
            .replace(/function\s+\w+\s*\([^)]*\)\s*\{[\s\S]*?\n\s*\}/g, '')
            .replace(/\bconst\s+\w+\s*=\s*\([^)]*\)\s*=>\s*\{[\s\S]*?\n\s*\}\s*;?/g, '');

        expect(withoutFunctions).not.toMatch(/\bdocument\./);
        expect(withoutFunctions).not.toMatch(/\blocalStorage\./);
    });
});
