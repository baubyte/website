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
                // Display/títulos: serif variable cálida con carácter,
                // combina con el verde sage orgánico de la paleta
                // `baubyte-*` de abajo.
                display: ['Fraunces', ...defaultTheme.fontFamily.serif],
            },
        },
    },
    plugins: [require('daisyui')],
    daisyui: {
        themes: [
            {
                'baubyte-light': {
                    primary: '#6b8f6a',
                    'primary-content': '#f5f6f2',
                    secondary: '#4f7a52',
                    'secondary-content': '#f5f6f2',
                    accent: '#dfe8dc',
                    'accent-content': '#23271f',
                    neutral: '#5c6355',
                    'neutral-content': '#f5f6f2',
                    'base-100': '#ffffff',
                    'base-200': '#f5f6f2',
                    'base-300': '#eceee6',
                    'base-content': '#23271f',
                    info: '#3abff8',
                    success: '#36d399',
                    warning: '#fbbd23',
                    error: '#f87272',

                    '--rounded-box': '0.75rem',
                    '--rounded-btn': '0.5rem',
                },
            },
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
