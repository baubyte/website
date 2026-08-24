import { describe, expect, test } from 'vitest';
import { render, screen } from '@testing-library/svelte';
import Home from './Home.svelte';

// Real props sent by `HomeController@index`: already resolved to a
// single language — `description`/`specialty`/`title`, never the raw
// `_es`/`_en` pair — since `Home.svelte` and its child components never
// need to know about locale suffixes.
const baseProps = {
    profile: {
        name: 'Martín',
        surname: 'Pared Baez',
        avatar: 'avatar.webp',
        email_contact: 'paredbaez.martin@gmail.com',
        description: 'Desarrollador Full Stack Senior.',
        specialty: 'Desarrollador Full Stack Senior',
        github_url: 'https://github.com/baubyte',
        linkedin_url: 'https://www.linkedin.com/in/mparedbaez/',
    },
    skills: [
        { id: 1, name: 'Laravel', percentage: 90 },
        { id: 2, name: 'Svelte', percentage: 70 },
    ],
    experiences: [
        {
            id: 1,
            company: 'Baubyte',
            specialty: 'Desarrollador Full Stack',
            description: 'Desarrollo de aplicaciones web.',
            start_date: '2020-01-01',
            end_date: null,
        },
    ],
    studies: [
        {
            id: 1,
            entity: 'UTN',
            title: 'Ingeniería en Sistemas',
            description: 'Carrera de grado.',
            start_date: '2015-03-01',
            end_date: '2021-12-01',
        },
    ],
};

describe('Home', () => {
    test('renders the real profile name from props', () => {
        render(Home, { props: baseProps });

        // The full name legitimately appears twice (Hero + the About
        // sidebar's "Details" block) — asserting at least one match
        // instead of a single exact node, since both occurrences are
        // correct by design, not a regression.
        expect(screen.getAllByText('Martín Pared Baez').length).toBeGreaterThan(0);
    });
});
