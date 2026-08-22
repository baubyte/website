<?php

/*
|--------------------------------------------------------------------------
| SEO Configuration
|--------------------------------------------------------------------------
|
| Per-page SEO metadata (title, description, keywords, canonical path) and
| Open Graph defaults, ported from the legacy CodeIgniter application's
| `app/Config/Baubyte/SEOConfig.php`. Structured JSON-LD (Schema.org) is NOT
| derived from this file: it is built from live `Profile`/`Skill` data in
| `app/Support/Seo/PersonSchema.php` so it always reflects the migrated
| database rather than static placeholder values.
|
*/

return [

    'pages' => [
        'home' => [
            'title' => 'Martín Pared Baez - Desarrollador Full Stack Senior | CV Online',
            'description' => 'Desarrollador Full Stack Senior con más de 8 años de experiencia en PHP, Laravel, CodeIgniter, JavaScript, React, Vue.js y bases de datos. Disponible para proyectos freelance y oportunidades laborales.',
            'keywords' => 'martín Pared baez, desarrollador full stack, php developer, laravel developer, codeigniter, javascript, react, vue.js, mysql, postgresql, freelancer, argentina, buenos aires, curriculum vitae, cv online',
            'canonical' => '/',
        ],
    ],

    /*
    |----------------------------------------------------------------------
    | Primary keywords
    |----------------------------------------------------------------------
    |
    | Used as a fallback when a page's own `keywords` entry is empty.
    |
    */
    'primary_keywords' => [
        'desarrollador full stack',
        'php developer',
        'laravel developer',
        'codeigniter developer',
        'javascript developer',
        'react developer',
        'vue.js developer',
        'mysql developer',
        'postgresql developer',
        'freelancer argentina',
        'desarrollador web',
        'programador php',
        'martín Pared baez',
        'baubyte',
        'desarrollo software',
        'cv online',
        'curriculum vitae',
    ],

    /*
    |----------------------------------------------------------------------
    | Open Graph defaults
    |----------------------------------------------------------------------
    */
    'default_og' => [
        'type' => 'profile',
        'locale' => 'es_ES',
        'site_name' => 'Martín Pared Baez - Desarrollador Full Stack',
        'image' => '/favicon.png',
        'image_width' => '1200',
        'image_height' => '630',
    ],

];
