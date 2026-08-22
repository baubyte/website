import { render } from '@testing-library/svelte';
import { afterEach, describe, expect, test } from 'vitest';
import Skills from './Skills.svelte';

describe('Skills', () => {
    afterEach(() => {
        document.body.innerHTML = '';
    });

    test('groups skills by the real skill.category column, not a hardcoded map', () => {
        const skills = [
            { id: 1, name: 'PHP', percentage: 90, category: 'Backend' },
            { id: 2, name: 'Svelte', percentage: 70, category: 'Frontend' },
            { id: 3, name: 'MySQL', percentage: 80, category: 'Backend' },
        ];

        const { getByText } = render(Skills, { props: { skills } });

        // Category headings come straight from the DB value — an owner
        // regrouping skills from Filament changes this without a deploy.
        expect(getByText('Backend')).toBeTruthy();
        expect(getByText('Frontend')).toBeTruthy();
    });

    test('falls back to "Otros" for a skill with no category set, without hiding it', () => {
        const skills = [{ id: 1, name: 'COBOL', percentage: 40, category: null }];

        const { getByText } = render(Skills, { props: { skills } });

        expect(getByText('Otros')).toBeTruthy();
    });
});
