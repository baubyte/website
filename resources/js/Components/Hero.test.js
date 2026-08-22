import { describe, expect, test } from 'vitest';
import { render, screen } from '@testing-library/svelte';
import Hero from './Hero.svelte';

/**
 * PR8c: Hero moved from a centered single-column layout to an asymmetric
 * two-column layout (text left, framed avatar right on desktop; stacked on
 * mobile). These tests lock down the two contract points that matter for a
 * layout-only change: (1) `Hero` still only needs `profile` — no new
 * required props — and (2) the responsive grid classes that make it collapse
 * to one column on small viewports are actually present, since jsdom cannot
 * evaluate real CSS media queries or layout/overflow.
 */
const profile = {
    name: 'Martín',
    surname: 'Pared Baez',
    avatar: 'avatar.webp',
    specialty: 'Desarrollador Full Stack Senior',
    github_url: 'https://github.com/baubyte',
    linkedin_url: 'https://www.linkedin.com/in/mparedbaez/',
};

describe('Hero', () => {
    test('renders the real profile name and specialty from props only', () => {
        render(Hero, { props: { profile } });

        expect(screen.getByText('Martín Pared Baez')).toBeInTheDocument();
        expect(
            screen.getByText('Desarrollador Full Stack Senior'),
        ).toBeInTheDocument();
    });

    test('the frame badges show the real top skill per category, not the specialty text', () => {
        // Real bug the owner caught: the floating badges only ever
        // duplicated `profile.specialty` text instead of surfacing real
        // per-category skill data — fixed by deriving each badge's label
        // from the highest-`percentage` skill in its category.
        const skills = [
            { name: 'PHP & Laravel', category: 'Backend', percentage: 90 },
            { name: 'Node.js & NestJS', category: 'Backend', percentage: 70 },
            { name: 'IA, RAG & Vector Stores', category: 'IA', percentage: 60 },
        ];

        render(Hero, { props: { profile, skills, yearsOfExperience: 16 } });

        expect(screen.getByText('PHP & Laravel')).toBeInTheDocument();
        expect(screen.getByText('IA, RAG & Vector Stores')).toBeInTheDocument();
        expect(screen.getByText('+16 años de experiencia')).toBeInTheDocument();
        expect(screen.queryByText('Node.js & NestJS')).not.toBeInTheDocument();
    });

    test('hides a category badge entirely when no skill exists for it, instead of showing empty content', () => {
        render(Hero, { props: { profile, skills: [] } });

        expect(screen.queryByText('PHP & Laravel')).not.toBeInTheDocument();
        expect(screen.queryByText('IA, RAG & Vector Stores')).not.toBeInTheDocument();
    });

    test('uses a responsive grid that collapses to a single column on small viewports', () => {
        const { container } = render(Hero, { props: { profile } });

        const grid = container.querySelector('.grid');

        expect(grid).not.toBeNull();
        // Single column by default (mobile-first), two columns from `md:` up.
        expect(grid.className).toMatch(/\bgrid-cols-1\b/);
        expect(grid.className).toMatch(/\bmd:grid-cols-2\b/);
    });

    test('the section clips its decorative elements so nothing can cause horizontal overflow', () => {
        const { container } = render(Hero, { props: { profile } });

        const section = container.querySelector('section');

        expect(section.className).toMatch(/\boverflow-hidden\b/);
    });

    test('the CTA row exposes the CV download link', () => {
        render(Hero, { props: { profile } });

        expect(
            screen.getByRole('link', { name: /download cv/i }),
        ).toHaveAttribute('href', '/download-cv');
    });
});
