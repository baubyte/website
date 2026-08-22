import { describe, expect, test } from 'vitest';
import { render, screen } from '@testing-library/svelte';
import StudyTimeline from './StudyTimeline.svelte';

describe('StudyTimeline', () => {
    test('renders "Presente" when end_date is null', () => {
        render(StudyTimeline, {
            props: {
                studies: [
                    {
                        id: 1,
                        entity: 'UTN',
                        title: 'Ingeniería en Sistemas',
                        description: 'Carrera de grado.',
                        start_date: '2015-03-01T00:00:00.000000Z',
                        end_date: null,
                    },
                ],
            },
        });

        expect(screen.getByText(/Presente/)).toBeInTheDocument();
    });

    test('renders the formatted end date when end_date is set (not "Presente")', () => {
        render(StudyTimeline, {
            props: {
                studies: [
                    {
                        id: 1,
                        entity: 'UTN',
                        title: 'Ingeniería en Sistemas',
                        description: 'Carrera de grado.',
                        start_date: '2015-03-01T00:00:00.000000Z',
                        end_date: '2021-12-01T00:00:00.000000Z',
                    },
                ],
            },
        });

        expect(screen.queryByText(/Presente/)).not.toBeInTheDocument();
    });
});
