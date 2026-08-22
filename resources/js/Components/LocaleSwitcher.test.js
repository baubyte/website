import { describe, expect, test } from 'vitest';
import { render, screen } from '@testing-library/svelte';
import LocaleSwitcher from './LocaleSwitcher.svelte';

describe('LocaleSwitcher', () => {
    test('renders the ES and EN options linking to the session locale route', () => {
        render(LocaleSwitcher, { props: { currentLocale: 'es' } });

        const es = screen.getByRole('link', { name: 'ES' });
        const en = screen.getByRole('link', { name: 'EN' });

        expect(es).toHaveAttribute('href', '/locale/es');
        expect(en).toHaveAttribute('href', '/locale/en');
    });

    test('marks the current locale as active', () => {
        render(LocaleSwitcher, { props: { currentLocale: 'en' } });

        expect(screen.getByRole('link', { name: 'EN' })).toHaveAttribute('aria-current', 'true');
        expect(screen.getByRole('link', { name: 'ES' })).not.toHaveAttribute('aria-current');
    });
});
