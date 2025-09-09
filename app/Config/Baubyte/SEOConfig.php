<?php 

namespace Config\Baubyte;

use CodeIgniter\Config\BaseConfig;

class SEOConfig extends BaseConfig
{
    /**
     * Configuración SEO por página
     */
    public array $pagesSEO = [
        'home' => [
            'title' => 'Martín Pared Baez - Desarrollador Full Stack Senior | CV Online',
            'description' => 'Desarrollador Full Stack Senior con más de 8 años de experiencia en PHP, Laravel, CodeIgniter, JavaScript, React, Vue.js y bases de datos. Disponible para proyectos freelance y oportunidades laborales.',
            'keywords' => 'martín Pared baez, desarrollador full stack, php developer, laravel developer, codeigniter, javascript, react, vue.js, mysql, postgresql, freelancer, argentina, buenos aires, curriculum vitae, cv online',
            'canonical' => '/'
        ],
        'about' => [
            'title' => 'Acerca de Mí - Martín Pared Baez | Desarrollador Full Stack',
            'description' => 'Conoce mi historia profesional como desarrollador Full Stack. Más de 8 años creando soluciones web innovadoras con tecnologías modernas y mejores prácticas de desarrollo.',
            'keywords' => 'acerca de mi, biografia profesional, desarrollador php, experiencia desarrollo web, full stack developer argentina',
            'canonical' => '/#about'
        ],
        'skills' => [
            'title' => 'Habilidades Técnicas - Tecnologías y Herramientas',
            'description' => 'Mis habilidades técnicas incluyen PHP, Laravel, CodeIgniter, JavaScript, React, Vue.js, MySQL, PostgreSQL, Git, Docker, y más. Experiencia en desarrollo frontend y backend.',
            'keywords' => 'habilidades técnicas, php, laravel, codeigniter, javascript, react, vue.js, mysql, postgresql, git, docker, html, css, bootstrap, desarrollo web',
            'canonical' => '/#skills'
        ],
        'experience' => [
            'title' => 'Experiencia Profesional - Proyectos y Trabajos Realizados',
            'description' => 'Mi experiencia profesional incluye desarrollo de aplicaciones web, sistemas de gestión, e-commerce, APIs REST, y soluciones personalizadas para diversos clientes y empresas.',
            'keywords' => 'experiencia laboral, proyectos desarrollados, desarrollo web profesional, aplicaciones php, sistemas de gestión, e-commerce, apis rest',
            'canonical' => '/#experience'
        ],
        'education' => [
            'title' => 'Formación Académica y Certificaciones',
            'description' => 'Mi formación incluye estudios en desarrollo de software, certificaciones en tecnologías web modernas y constante actualización en las últimas tendencias del desarrollo.',
            'keywords' => 'formación académica, educación, certificaciones, estudios programación, desarrollo software, cursos online, capacitación técnica',
            'canonical' => '/#education'
        ],
        'contact' => [
            'title' => 'Contacto - Martín Pared Baez | Desarrollador Full Stack',
            'description' => 'Ponte en contacto conmigo para discutir proyectos de desarrollo web, oportunidades laborales o colaboraciones. Disponible para proyectos freelance y tiempo completo.',
            'keywords' => 'contacto desarrollador, freelancer php, contratar desarrollador, proyectos web, colaboraciones, oportunidades laborales, martin Pared baez contacto',
            'canonical' => '/#contact'
        ],
        'download_cv' => [
            'title' => 'Descargar CV PDF - Martín Pared Baez',
            'description' => 'Descarga mi curriculum vitae en formato PDF con toda mi experiencia profesional, habilidades técnicas y formación académica actualizada.',
            'keywords' => 'descargar cv, curriculum vitae pdf, cv desarrollador, resume download, martin Pared baez cv',
            'canonical' => '/download-cv'
        ],
    ];

    /**
     * Información personal para Schema.org
     */
    public array $businessInfo = [
        'name' => 'Martín Pared Baez',
        'description' => 'Desarrollador Full Stack Senior especializado en PHP, Laravel, CodeIgniter, JavaScript, React y Vue.js',
        'url' => 'https://baubyte.ar',
        'telephone' => '+54-11-XXXX-XXXX', // Actualizar con tu número real
        'email' => 'paredbaez.martin@gmail.com',
        'address' => [
            'addressLocality' => 'Buenos Aires',
            'addressRegion' => 'CABA',
            'addressCountry' => 'AR'
        ],
        'geo' => [
            'latitude' => '-34.6118',
            'longitude' => '-58.3960'
        ],
        'jobTitle' => 'Desarrollador Full Stack Senior',
        'worksFor' => 'Freelancer',
        'socialMedia' => [
            'github' => 'https://github.com/baubyte',
            'linkedin' => 'https://www.linkedin.com/in/mparedbaez/',
            'instagram' => 'https://instagram.com/baubyte' // Actualizar si tienes
        ],
        'skills' => [
            'PHP', 'Laravel', 'CodeIgniter', 'JavaScript', 'React', 'Vue.js', 
            'MySQL', 'PostgreSQL', 'Git', 'Docker', 'HTML5', 'CSS3', 'Bootstrap'
        ],
        'serviceType' => 'Desarrollo de Software',
        'areaServed' => [
            'type' => 'Country',
            'name' => 'Argentina'
        ],
        'availability' => 'Disponible para proyectos freelance',
        'experienceLevel' => 'Senior',
        'yearsOfExperience' => '8+'
    ];

    /**
     * Configuración Open Graph por defecto
     */
    public array $defaultOG = [
        'type' => 'profile',
        'locale' => 'es_ES',
        'site_name' => 'Martín Pared Baez - Desarrollador Full Stack',
        'image' => '/favicon.png', // Actualizar con tu imagen de perfil
        'image:width' => '1200',
        'image:height' => '630',
        'profile:first_name' => 'Martín',
        'profile:last_name' => 'Pared Baez',
        'profile:username' => 'baubyte',
        'profile:gender' => 'male'
    ];

    /**
     * Palabras clave principales para IA y SEO
     */
    public array $primaryKeywords = [
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
        'curriculum vitae'
    ];

    /**
     * Configuración para diferentes tipos de contenido
     */
    public array $contentTypes = [
        'skill_category' => [
            'title_template' => 'Habilidades en {category} - Martín Pared Baez',
            'description_template' => 'Experiencia profesional en {category}. Conocimientos avanzados y proyectos realizados con estas tecnologías en desarrollo web full stack.',
            'keywords_template' => '{category}, desarrollador {category}, programador {category}, experiencia {category}, proyectos {category}'
        ],
        'project_category' => [
            'title_template' => 'Proyectos de {category} - Portfolio Desarrollador',
            'description_template' => 'Portfolio de proyectos desarrollados en {category}. Soluciones web innovadoras y aplicaciones personalizadas para diversos clientes.',
            'keywords_template' => 'proyectos {category}, portfolio {category}, desarrollo {category}, aplicaciones {category}'
        ],
        'experience_type' => [
            'title_template' => 'Experiencia en {type} - Desarrollo Profesional',
            'description_template' => 'Mi experiencia profesional en {type} incluye diversos proyectos y responsabilidades que han fortalecido mis habilidades técnicas.',
            'keywords_template' => 'experiencia {type}, trabajo {type}, proyectos {type}, desarrollo {type}'
        ]
    ];
}
