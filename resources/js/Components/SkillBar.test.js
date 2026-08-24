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

    test('renders the server-resolved icon_data when present, instead of the legacy getSkillMeta icon', () => {
        const iconData = { body: '<path d="M0 0h1v1H0z" />', width: 24, height: 24 };

        const { container } = render(SkillBar, {
            props: {
                skill: { id: 1, name: 'Laravel', percentage: 65, icon_data: iconData },
            },
        });

        // `@iconify/svelte`'s `Icon` renders the given `body` verbatim
        // inside the `<svg>` it produces — asserting on that verbatim
        // markup confirms `icon_data` (not `getSkillMeta`'s devicon
        // import) actually drove rendering.
        expect(container.querySelector('svg path[d="M0 0h1v1H0z"]')).not.toBeNull();
    });

    test('falls back to getSkillMeta when icon_data is null', () => {
        render(SkillBar, {
            props: { skill: { id: 1, name: 'JAVA', percentage: 65, icon_data: null } },
        });

        // `getSkillMeta('JAVA')` resolves the real Java devicon logo — its
        // imported SVG data always renders an `<svg>`, so this only needs
        // to confirm an icon rendered at all through the legacy path (and
        // not the unrelated `icon_data` fixture markup from the test above).
        expect(document.querySelector('svg')).not.toBeNull();
        expect(document.querySelector('svg path[d="M0 0h1v1H0z"]')).toBeNull();
    });
});
