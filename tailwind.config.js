import defaultTheme from 'tailwindcss/defaultTheme';

/** @type {import('tailwindcss').Config} */
export default {
    content: [
        './vendor/laravel/framework/src/Illuminate/Pagination/resources/views/*.blade.php',
        './storage/framework/views/*.php',
        './resources/**/*.{blade.php,svelte,js}',
    ],
    theme: {
        extend: {
            fontFamily: {
                // Cuerpo: sans-serif humanista, limpia — deliberadamente
                // NO Inter/Space Grotesk (el default genérico de todo sitio
                // hecho con IA). Servida vía Google Fonts CDN + `preconnect`
                // (ver `resources/views/app.blade.php`) — autohospedar los
                // woff2 quedó fuera de esta unidad por tiempo/complejidad.
                sans: ['Public Sans', ...defaultTheme.fontFamily.sans],
                // Display/títulos: Orbitron — la MISMA fuente que usa el
                // sitio de referencia (gustavomorinaga.dev, confirmado
                // contra su HTML real) para su título con glow. Se copia
                // la fuente tal cual, coloreada con la paleta sage propia
                // en vez del neón de la referencia.
                display: ['Orbitron', ...defaultTheme.fontFamily.sans],
                // Eyebrow estilo código (`const developer = "...";` en
                // Hero.svelte): Fira Code, también tomada de la referencia.
                mono: ['Fira Code', ...defaultTheme.fontFamily.mono],
            },
        },
    },
    plugins: [require('daisyui')],
    daisyui: {
        // Dark is the only theme (2026-08-22, owner request): the site
        // never toggles — `ThemeToggle.svelte` and its light/dark
        // localStorage logic were removed rather than defaulted, so
        // `baubyte-light` isn't reachable from anywhere in the UI anymore.
        themes: [
            {
                'baubyte-dark': {
                    primary: '#8fb389',
                    'primary-content': '#151812',
                    secondary: '#a8c7a3',
                    'secondary-content': '#151812',
                    accent: '#26301f',
                    'accent-content': '#e6e8de',
                    neutral: '#9aa290',
                    'neutral-content': '#151812',
                    'base-100': '#1c2018',
                    'base-200': '#151812',
                    'base-300': '#22271c',
                    'base-content': '#e6e8de',
                    info: '#3abff8',
                    success: '#36d399',
                    warning: '#fbbd23',
                    error: '#f87272',

                    '--rounded-box': '0.75rem',
                    '--rounded-btn': '0.5rem',
                },
            },
        ],
        darkTheme: 'baubyte-dark',
    },
};
