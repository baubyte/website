import { describe, expect, test } from 'vitest';
import { render, screen } from '@testing-library/svelte';
import Home from './Home.svelte';

const baseProps = {
    profile: {
        name: 'Martín',
        surname: 'Pared Baez',
        avatar: 'avatar.webp',
        email_contact: 'paredbaez.martin@gmail.com',
        description_es: 'Desarrollador Full Stack Senior.',
        description_en: 'Senior Full Stack Developer.',
        specialty_es: 'Desarrollador Full Stack Senior',
        specialty_en: 'Senior Full Stack Developer',
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
            specialty_es: 'Desarrollador Full Stack',
            description_es: 'Desarrollo de aplicaciones web.',
            start_date: '2020-01-01',
            end_date: null,
        },
    ],
    studies: [
        {
            id: 1,
            entity: 'UTN',
            title_es: 'Ingeniería en Sistemas',
            description_es: 'Carrera de grado.',
            start_date: '2015-03-01',
            end_date: '2021-12-01',
        },
    ],
};

describe('Home', () => {
    test('renders the real profile name from props', () => {
        render(Home, { props: baseProps });

        expect(
            screen.getByText('Martín Pared Baez'),
        ).toBeInTheDocument();
    });
});
