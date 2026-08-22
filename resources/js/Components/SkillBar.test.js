import { describe, expect, test } from 'vitest';
import { render, screen } from '@testing-library/svelte';
import SkillBar from './SkillBar.svelte';

describe('SkillBar', () => {
    test('renders the bar width proportional to the percentage', () => {
        render(SkillBar, {
            props: { skill: { id: 1, name: 'Laravel', percentage: 65 } },
        });

        const fill = screen.getByTestId('skill-bar-fill');

        expect(fill).toHaveStyle('width: 65%');
    });
});
