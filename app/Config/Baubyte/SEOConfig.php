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
            'title' => 'Roma2 - Alquiler de Utilería y Props para Producciones Audiovisuales',
            'description' => 'Roma2 - Alquiler de utilería y props para producciones audiovisuales en Buenos Aires. Elementos decorativos para cine, televisión, publicidad y fotografía. Catálogo online con más de 1000 productos disponibles.',
            'keywords' => 'alquiler utilería, props cine, utilería televisión, decoración audiovisual, alquiler decoración, props publicidad, utilería argentina, roma2 rental, producción audiovisual, elementos decorativos, atrezzo, utilería Buenos Aires',
            'canonical' => '/'
        ],
        'products' => [
            'title' => 'Catálogo de Productos - Utilería y Props para Alquiler',
            'description' => 'Explore nuestro catálogo completo de utilería y props para alquiler. Más de 1000 elementos organizados por categorías para producciones de cine, TV y publicidad.',
            'keywords' => 'catálogo utilería, props alquiler, elementos decorativos, utilería cine, props televisión, decoración audiovisual, alquiler props Buenos Aires',
            'canonical' => '/productos'
        ],
        'rent_condition' => [
            'title' => 'Condiciones de Alquiler - Términos y Proceso',
            'description' => 'Condiciones generales para el alquiler de utilería y props. Proceso paso a paso, garantías, términos de devolución y políticas de pago para productoras.',
            'keywords' => 'condiciones alquiler, términos utilería, proceso alquiler props, garantías alquiler, políticas alquiler audiovisual',
            'canonical' => '/condiciones-alquiler'
        ],
        'contact' => [
            'title' => 'Contacto - Roma2 Rental',
            'description' => 'Contacta con Roma2 para alquilar utilería y props audiovisuales. Ubicados en San Cristóbal, CABA. Email: roma2rental@gmail.com. Tel: (011) 1533398547.',
            'keywords' => 'contacto roma2, alquiler utilería contacto, props buenos aires contacto, roma2rental email, utilería san cristóbal',
            'canonical' => '/contacto'
        ],
        'terms_conditions' => [
            'title' => 'Términos y Condiciones',
            'description' => 'Términos y condiciones para el alquiler de utilería y props en Roma2.',
            'keywords' => 'términos y condiciones, alquiler utilería, alquiler props, políticas de alquiler',
            'canonical' => '/terminos-y-condiciones'
        ],
    ];

    /**
     * Información de la empresa para Schema.org
     */
    public array $businessInfo = [
        'name' => 'Roma2 Rental',
        'description' => 'Alquiler de utilería y props para producciones audiovisuales, cine, televisión y publicidad',
        'url' => 'https://roma2.com.ar',
        'telephone' => '+54-11-3339-8547',
        'email' => 'roma2rental@gmail.com',
        'address' => [
            'streetAddress' => 'Alberti 707, Piso 3 Depto 7',
            'addressLocality' => 'San Cristóbal',
            'addressRegion' => 'CABA',
            'postalCode' => 'C1223',
            'addressCountry' => 'AR'
        ],
        'geo' => [
            'latitude' => '-34.6118',
            'longitude' => '-58.3960'
        ],
        'openingHours' => 'Mo-Fr 09:00-18:00',
        'socialMedia' => [
            'instagram' => 'https://www.instagram.com/roma2rental/',
            'whatsapp' => 'https://wa.me/5491133398547'
        ],
        'serviceType' => 'Alquiler de utilería audiovisual',
        'areaServed' => [
            'type' => 'GeoCircle',
            'latitude' => '-34.6118',
            'longitude' => '-58.3960',
            'radius' => '50000'
        ],
        'offerCatalog' => [
            'name' => 'Catálogo de Utilería y Props',
            'services' => [
                [
                    'name' => 'Alquiler de utilería para cine',
                    'description' => 'Props y elementos decorativos para producciones cinematográficas'
                ],
                [
                    'name' => 'Alquiler de utilería para televisión',
                    'description' => 'Elementos decorativos para programas de TV y series'
                ],
                [
                    'name' => 'Alquiler de utilería para publicidad',
                    'description' => 'Props para comerciales y campañas publicitarias'
                ]
            ]
        ]
    ];

    /**
     * Configuración Open Graph por defecto
     */
    public array $defaultOG = [
        'type' => 'website',
        'locale' => 'es_ES',
        'site_name' => 'Roma2 Rental',
        'image' => '/assets/web/meta/roma2icon.png',
        'image:width' => '1200',
        'image:height' => '630'
    ];

    /**
     * Palabras clave principales para IA y SEO
     */
    public array $primaryKeywords = [
        'alquiler utilería',
        'props audiovisuales',
        'utilería cine',
        'props televisión',
        'decoración audiovisual',
        'atrezzo alquiler',
        'elementos decorativos',
        'producción audiovisual',
        'utilería Buenos Aires',
        'props Argentina',
        'alquiler decoración',
        'utilería publicitaria'
    ];

    /**
     * Configuración para diferentes tipos de contenido
     */
    public array $contentTypes = [
        'product_category' => [
            'title_template' => 'Utilería y Props de {category} - Alquiler Roma2',
            'description_template' => 'Alquiler de utilería y props de {category} para producciones audiovisuales. Elementos decorativos profesionales para cine, TV y publicidad en Buenos Aires.',
            'keywords_template' => 'utilería {category}, props {category}, alquiler {category}, decoración {category}, elementos {category}'
        ],
        'product_subcategory' => [
            'title_template' => '{subcategory} de {category} - Alquiler de Utilería Roma2',
            'description_template' => 'Alquiler de {subcategory} de {category} para producciones audiovisuales. Props y elementos decorativos especializados disponibles en Buenos Aires.',
            'keywords_template' => '{subcategory} {category}, alquiler {subcategory}, props {subcategory}, utilería {subcategory}'
        ]
    ];
}
