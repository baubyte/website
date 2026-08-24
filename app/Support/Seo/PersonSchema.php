<?php

namespace App\Support\Seo;

use App\Models\Profile;
use App\Models\Skill;
use App\Support\Locale\Locale;
use Illuminate\Support\Collection;

/**
 * Builds a Schema.org `Person` JSON-LD structure from live `Profile`/`Skill`
 * data, semantically equivalent to the legacy CodeIgniter app's
 * `schema_org_person()` helper (`app/Helpers/seo_helper.php`), but sourced
 * from the migrated database instead of a static config class so it always
 * reflects the current profile. `description`/`jobTitle` are resolved to
 * the given locale since `Profile` carries real `_es`/`_en` data, unlike
 * the static page-metadata copy in `config/seo.php`.
 */
class PersonSchema
{
    /**
     * @param  Collection<int, Skill>  $skills
     * @return array<string, mixed>
     */
    public static function build(?Profile $profile, Collection $skills, string $locale = Locale::DEFAULT): array
    {
        if ($profile === null) {
            return [
                '@context' => 'https://schema.org',
                '@type' => 'Person',
                'name' => config('app.name'),
            ];
        }

        $localized = $profile->toLocalizedArray($locale);
        $name = trim("{$localized['name']} {$localized['surname']}");

        $schema = [
            '@context' => 'https://schema.org',
            '@type' => 'Person',
            'name' => $name,
            'description' => $localized['description'],
            'url' => config('app.url'),
            'email' => $localized['email_contact'],
            'jobTitle' => $localized['specialty'],
            'worksFor' => [
                '@type' => 'Organization',
                'name' => 'Freelancer',
            ],
            'sameAs' => array_values(array_filter([
                $localized['github_url'],
                $localized['linkedin_url']
            ])),
            'knowsAbout' => $skills->pluck('name')->values()->all(),
        ];

        return array_filter(
            $schema,
            fn ($value) => $value !== null && $value !== '' && $value !== []
        );
    }

    /**
     * @param  array<string, mixed>  $schema
     */
    public static function toJsonLdScript(array $schema): string
    {
        return '<script type="application/ld+json">'."\n"
            .json_encode($schema, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES)
            ."\n".'</script>';
    }
}
