<?php

use Config\Roma2\SEOConfig;

if (!function_exists('seo_meta_tags')) {
    /**
     * Genera meta tags optimizados para SEO basados en la página actual
     * 
     * @param string $page_key Clave de la página ('home', 'products', 'contact', etc.)
     * @param array $custom_data Datos personalizados para sobrescribir configuración
     * @return string HTML con meta tags
     */
    function seo_meta_tags(string $page_key = 'home', array $custom_data = []): string
    {
        /** @var SEOConfig $seoConfig */
        $seoConfig = config(SEOConfig::class);
        $pageConfig = $seoConfig->pagesSEO[$page_key] ?? $seoConfig->pagesSEO['home'];
        // Merge con datos personalizados
        $pageConfig = array_merge($pageConfig, $custom_data);
        $html = '';
        // Meta description
        if (!empty($pageConfig['description'])) {
            $html .= '<meta name="description" content="' . esc($pageConfig['description']) . '">' . "\n    ";
        }
        // Meta keywords
        if (!empty($pageConfig['keywords'])) {
            $html .= '<meta name="keywords" content="' . esc($pageConfig['keywords']) . '">' . "\n    ";
        } else {
            // Si no hay keywords específicos, usar los principales de la configuración
            $primaryKeywords = implode(', ', $seoConfig->primaryKeywords);
            $html .= '<meta name="keywords" content="' . esc($primaryKeywords) . '">' . "\n    ";
        }
        // Canonical URL
        if (!empty($pageConfig['canonical'])) {
            $html .= '<link rel="canonical" href="' . base_url($pageConfig['canonical']) . '">' . "\n    ";
        }
        // Open Graph adicionales específicos de página
        if (!empty($pageConfig['title'])) {
            $html .= '<meta property="og:title" content="' . esc($pageConfig['title']) . '">' . "\n    ";
        }
        if (!empty($pageConfig['description'])) {
            $html .= '<meta property="og:description" content="' . esc($pageConfig['description']) . '">' . "\n    ";
        }
        // Open Graph desde configuración default
        $defaultOG = $seoConfig->defaultOG;
        $html .= '<meta property="og:type" content="' . $defaultOG['type'] . '">' . "\n    ";
        $html .= '<meta property="og:locale" content="' . $defaultOG['locale'] . '">' . "\n    ";
        $html .= '<meta property="og:site_name" content="' . esc($defaultOG['site_name']) . '">' . "\n    ";
        $html .= '<meta property="og:image" content="' . base_url($defaultOG['image']) . '">' . "\n    ";
        $html .= '<meta property="og:image:width" content="' . $defaultOG['image:width'] . '">' . "\n    ";
        $html .= '<meta property="og:image:height" content="' . $defaultOG['image:height'] . '">' . "\n    ";
        $html .= '<meta property="og:url" content="' . current_url() . '">' . "\n    ";
        // Twitter Cards dinámicos
        $html .= '<meta name="twitter:title" content="' . esc($pageConfig['title'] ?? $defaultOG['site_name']) . '">' . "\n    ";
        $html .= '<meta name="twitter:description" content="' . esc($pageConfig['description'] ?? 'Alquiler de utilería y props audiovisuales') . '">' . "\n    ";
        $html .= '<meta name="twitter:image" content="' . base_url($defaultOG['image']) . '">' . "\n    ";
        return rtrim($html);
    }
}

if (!function_exists('generate_category_seo')) {
    /**
     * Genera meta tags para páginas de categorías
     * 
     * @param string $category_name Nombre de la categoría
     * @param string $category_slug Slug de la categoría
     * @param string $subcategory_name Nombre de subcategoría (opcional)
     * @return array Datos SEO para la categoría
     */
    function generate_category_seo(string $category_name, string $category_slug, string $subcategory_name = ''): array
    {
        /** @var SEOConfig $seoConfig */
        $seoConfig = config(SEOConfig::class);
        if (!empty($subcategory_name)) {
            // SEO para subcategoría
            $template = $seoConfig->contentTypes['product_subcategory'];
            $specificKeywords = str_replace(['{subcategory}', '{category}'], [strtolower($subcategory_name), strtolower($category_name)], $template['keywords_template']);
            return [
                'title' => str_replace(['{subcategory}', '{category}'], [$subcategory_name, $category_name], $template['title_template']),
                'description' => str_replace(['{subcategory}', '{category}'], [$subcategory_name, $category_name], $template['description_template']),
                'keywords' => generate_dynamic_keywords(explode(', ', $specificKeywords)),
                'canonical' => '/productos/' . $category_slug . '/' . url_title($subcategory_name, '-', true)
            ];
        } else {
            // SEO para categoría
            $template = $seoConfig->contentTypes['product_category'];
            $specificKeywords = str_replace('{category}', strtolower($category_name), $template['keywords_template']);
            return [
                'title' => str_replace('{category}', $category_name, $template['title_template']),
                'description' => str_replace('{category}', $category_name, $template['description_template']),
                'keywords' => generate_dynamic_keywords(explode(', ', $specificKeywords)),
                'canonical' => '/productos/' . $category_slug
            ];
        }
    }
}

if (!function_exists('schema_org_business')) {
    /**
     * Genera el schema.org para LocalBusiness completo
     *
     * @return string JSON-LD estructurado
     */
    function schema_org_business(): string
    {
        /** @var SEOConfig $seoConfig */
        $seoConfig = config(SEOConfig::class);
        $business = $seoConfig->businessInfo;
        $schema = [
            "@context" => "https://schema.org",
            "@type" => "LocalBusiness",
            "name" => $business['name'],
            "description" => $business['description'],
            "url" => $business['url'],
            "telephone" => $business['telephone'],
            "email" => $business['email'],
            "address" => [
                "@type" => "PostalAddress",
                "streetAddress" => $business['address']['streetAddress'],
                "addressLocality" => $business['address']['addressLocality'],
                "addressRegion" => $business['address']['addressRegion'],
                "postalCode" => $business['address']['postalCode'],
                "addressCountry" => $business['address']['addressCountry']
            ],
            "geo" => [
                "@type" => "GeoCoordinates",
                "latitude" => $business['geo']['latitude'],
                "longitude" => $business['geo']['longitude']
            ],
            "openingHours" => $business['openingHours'],
            "sameAs" => array_values($business['socialMedia']),
            "serviceType" => $business['serviceType'],
            "areaServed" => [
                "@type" => "GeoCircle",
                "geoMidpoint" => [
                    "@type" => "GeoCoordinates",
                    "latitude" => $business['geo']['latitude'],
                    "longitude" => $business['geo']['longitude']
                ],
                "geoRadius" => $business['areaServed']['radius']
            ],
            "hasOfferCatalog" => [
                "@type" => "OfferCatalog",
                "name" => $business['offerCatalog']['name'],
                "itemListElement" => array_map(function($service) {
                    return [
                        "@type" => "Offer",
                        "itemOffered" => [
                            "@type" => "Service",
                            "name" => $service['name'],
                            "description" => $service['description']
                        ]
                    ];
                }, $business['offerCatalog']['services'])
            ]
        ];
        return json_encode($schema, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
    }
}

if (!function_exists('ai_optimized_content_meta')) {
    /**
     * Genera meta tags específicos para optimización con IA
     * 
     * @param array $content_data Datos del contenido
     * @return string HTML con meta tags para IA
     */
    function ai_optimized_content_meta(array $content_data = []): string
    {
        $html = '';
        
        // Meta tags específicos para IA y búsquedas semánticas
        $ai_metas = [
            'content:type' => $content_data['type'] ?? 'catalog',
            'content:industry' => 'audiovisual production, entertainment, advertising',
            'content:service' => 'rental, props, production equipment',
            'content:location' => 'Buenos Aires, Argentina, CABA',
            'content:language' => 'spanish',
            'content:target_audience' => 'film producers, TV directors, advertising agencies, photographers',
            'business:category' => 'equipment rental, audiovisual services, production support',
            'search:intent' => 'commercial, rental services, equipment search'
        ];
        foreach ($ai_metas as $name => $content) {
            $html .= '<meta name="' . $name . '" content="' . esc($content) . '">' . "\n    ";
        }
        return rtrim($html);
    }
}

if (!function_exists('schema_org_script')) {
    /**
     * Genera el script tag completo con Schema.org JSON-LD
     * 
     * @return string Script tag con JSON-LD
     */
    function schema_org_script(): string
    {
        return '<script type="application/ld+json">' . "\n" . schema_org_business() . "\n" . '</script>';
    }
}

if (!function_exists('schema_org_product_category')) {
    /**
     * Genera Schema.org para páginas de categorías de productos
     * 
     * @param string $category_name Nombre de la categoría
     * @param string $category_description Descripción de la categoría
     * @param array $products Lista de productos (opcional)
     * @return string JSON-LD para categoría de productos
     */
    function schema_org_product_category(string $category_name, string $category_description, array $products = []): string
    {
        /** @var SEOConfig $seoConfig */
        $seoConfig = config(SEOConfig::class);
        $business = $seoConfig->businessInfo;
        $schema = [
            "@context" => "https://schema.org",
            "@type" => "CollectionPage",
            "name" => $category_name,
            "description" => $category_description,
            "url" => current_url(),
            "mainEntity" => [
                "@type" => "ItemList",
                "name" => "Utilería de " . $category_name,
                "description" => $category_description,
                "numberOfItems" => count($products)
            ],
            "provider" => [
                "@type" => "LocalBusiness",
                "name" => $business['name'],
                "url" => $business['url'],
                "telephone" => $business['telephone'],
                "email" => $business['email']
            ]
        ];
        // Si hay productos, agregar algunos como ejemplo
        if (!empty($products)) {
            $schema["mainEntity"]["itemListElement"] = array_slice(array_map(function($product, $index) {
                return [
                    "@type" => "ListItem",
                    "position" => $index + 1,
                    "item" => [
                        "@type" => "Product",
                        "name" => $product->title ?? 'Producto de utilería',
                        "description" => $product->description ?? 'Elemento decorativo para alquiler',
                        "category" => "Utilería audiovisual"
                    ]
                ];
            }, $products, array_keys($products)), 0, 10); // Solo primeros 10 productos
        } 
        return '<script type="application/ld+json">' . "\n" . json_encode($schema, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES) . "\n" . '</script>';
    }
}

if (!function_exists('schema_org_breadcrumb')) {
    /**
     * Genera Schema.org para breadcrumbs de navegación
     * 
     * @param array $breadcrumbs Array de breadcrumbs [['name' => 'Nombre', 'url' => 'URL'], ...]
     * @return string JSON-LD para breadcrumbs
     */
    function schema_org_breadcrumb(array $breadcrumbs): string
    {
        if (empty($breadcrumbs)) {
            return '';
        }
        $schema = [
            "@context" => "https://schema.org",
            "@type" => "BreadcrumbList",
            "itemListElement" => array_map(function($breadcrumb, $index) {
                return [
                    "@type" => "ListItem",
                    "position" => $index + 1,
                    "name" => $breadcrumb['name'],
                    "item" => $breadcrumb['url']
                ];
            }, $breadcrumbs, array_keys($breadcrumbs))
        ];
        return '<script type="application/ld+json">' . "\n" . json_encode($schema, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES) . "\n" . '</script>';
    }
}

if (!function_exists('generate_dynamic_keywords')) {
    /**
     * Genera keywords dinámicos combinando las palabras clave principales con términos específicos
     * 
     * @param array $specific_terms Términos específicos de la página
     * @param bool $include_primary Incluir palabras clave principales
     * @return string Keywords separados por comas
     */
    function generate_dynamic_keywords(array $specific_terms = [], bool $include_primary = true): string
    {
        /** @var SEOConfig $seoConfig */
        $seoConfig = config(SEOConfig::class);
        $keywords = [];
        if ($include_primary) {
            $keywords = array_merge($keywords, $seoConfig->primaryKeywords);
        }
        if (!empty($specific_terms)) {
            $keywords = array_merge($keywords, $specific_terms);
        }
        // Eliminar duplicados y retornar
        return implode(', ', array_unique($keywords));
    }
}
