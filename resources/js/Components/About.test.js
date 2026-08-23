import { describe, expect, test } from 'vitest';
import { render, screen } from '@testing-library/svelte';
import About from './About.svelte';

/**
 * The reference site (gustavomorinaga.dev/about) shows birthdate/age,
 * location, and "random facts" in its sidebar — fields the `Profile` model
 * does NOT have. This unit deliberately only renders real `Profile` data:
 * full name, specialty, contact email, social links, and languages (when
 * present). No personal data field is invented.
 */
const profile = {
    name: 'Martín',
    surname: 'Pared Baez',
    specialty: 'Desarrollador Full Stack Senior',
    email_contact: 'paredbaez.martin@gmail.com',
    description: 'Desarrollador con experiencia en Laravel y Svelte.',
    language: 'Español, Inglés',
    github_url: 'https://github.com/baubyte',
    linkedin_url: 'https://www.linkedin.com/in/mparedbaez/',
};

describe('About', () => {
    test('renders the real Profile fields in the sidebar', () => {
        render(About, { props: { profile } });

        expect(screen.getByText('Martín Pared Baez')).toBeInTheDocument();
        expect(screen.getByText('Desarrollador Full Stack Senior')).toBeInTheDocument();
        expect(screen.getByText('paredbaez.martin@gmail.com')).toBeInTheDocument();
        expect(screen.getByText('Español, Inglés')).toBeInTheDocument();
    });

    test('renders the social links reused from Hero (github/linkedin)', () => {
        render(About, { props: { profile } });

        expect(screen.getByRole('link', { name: /github/i })).toHaveAttribute(
            'href',
            profile.github_url,
        );
        expect(screen.getByRole('link', { name: /linkedin/i })).toHaveAttribute(
            'href',
            profile.linkedin_url,
        );
    });

    test('never invents personal data fields the Profile model does not have', () => {
        render(About, { props: { profile } });

        // No birthdate/age, no location, no "random facts" section — these
        // don't exist on the Profile model and must never be fabricated.
        expect(screen.queryByText(/años/i)).not.toBeInTheDocument();
        expect(screen.queryByText(/ubicaci[oó]n/i)).not.toBeInTheDocument();
        expect(screen.queryByText(/random/i)).not.toBeInTheDocument();
    });

    test('omits the language row entirely when the profile has no language data', () => {
        const { language, ...withoutLanguage } = profile;

        render(About, { props: { profile: withoutLanguage } });

        expect(screen.queryByText(/idiomas/i)).not.toBeInTheDocument();
    });
});
