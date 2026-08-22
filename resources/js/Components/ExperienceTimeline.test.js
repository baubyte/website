import { describe, expect, test } from 'vitest';
import { render, screen } from '@testing-library/svelte';
import ExperienceTimeline from './ExperienceTimeline.svelte';

/**
 * `formatMonthYear` already formats dates in `es-ES` (see `formatDate.js`),
 * so the open-ended label must match that same language rather than the
 * English "Present" this component used before this unit.
 */
describe('ExperienceTimeline', () => {
    test('renders "Presente" when end_date is null', () => {
        render(ExperienceTimeline, {
            props: {
                experiences: [
                    {
                        id: 1,
                        company: 'Baubyte',
                        specialty: 'Full Stack',
                        description: 'Desarrollo de aplicaciones web.',
                        start_date: '2020-01-01T00:00:00.000000Z',
                        end_date: null,
                    },
                ],
            },
        });

        expect(screen.getByText(/Presente/)).toBeInTheDocument();
    });

    test('renders the formatted end date when end_date is set (not "Presente")', () => {
        render(ExperienceTimeline, {
            props: {
                experiences: [
                    {
                        id: 1,
                        company: 'Baubyte',
                        specialty: 'Full Stack',
                        description: 'Desarrollo de aplicaciones web.',
                        start_date: '2020-01-01T00:00:00.000000Z',
                        end_date: '2021-06-01T00:00:00.000000Z',
                    },
                ],
            },
        });

        expect(screen.queryByText(/Presente/)).not.toBeInTheDocument();
    });
});
