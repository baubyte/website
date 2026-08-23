<?php

namespace Tests\Feature\Home;

use App\Models\Profile;
use App\Models\Skill;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

/**
 * Verifies the SEO / JSON-LD partial rendered from Blade (see
 * `resources/views/partials/seo.blade.php`) reaches the raw HTML response
 * for `/`, independent of whether the Inertia/Svelte client ever mounts.
 *
 * Semantic equivalence is checked against the legacy CodeIgniter app's
 * `schema_org_script()` (`app/Helpers/seo_helper.php`) and `SEOConfig::$pagesSEO['home']`
 * (`app/Config/Baubyte/SEOConfig.php`) — same `@type`/fields, not identical
 * text, since the legacy version reads from a static config class while
 * this one is built from the migrated `Profile`/`Skill` rows.
 */
class HomeSeoTest extends TestCase
{
    use RefreshDatabase;

    public function test_home_html_contains_the_json_ld_person_schema_from_the_profile(): void
    {
        Profile::create([
            'name' => 'Martín',
            'surname' => 'Pared Baez',
            'avatar' => 'avatar.webp',
            'email_contact' => 'paredbaez.martin@gmail.com',
            'description_es' => 'Desarrollador Full Stack Senior.',
            'description_en' => 'Senior Full Stack Developer.',
            'specialty_es' => 'Desarrollador Full Stack Senior',
            'specialty_en' => 'Senior Full Stack Developer',
            'language_es' => 'Español nativo',
            'language_en' => 'English fluent',
            'github_url' => 'https://github.com/baubyte',
            'linkedin_url' => 'https://www.linkedin.com/in/mparedbaez/',
            'instagram_url' => 'https://instagram.com/baubyte',
        ]);

        Skill::create(['name' => 'Laravel', 'percentage' => 90]);

        $html = $this->get('/')->getContent();

        $this->assertStringContainsString('application/ld+json', $html);
        $this->assertStringContainsString('"@type": "Person"', $html);
        $this->assertStringContainsString('"name": "Martín Pared Baez"', $html);
        $this->assertStringContainsString('"jobTitle": "Desarrollador Full Stack Senior"', $html);
        $this->assertStringContainsString('"knowsAbout"', $html);
        $this->assertStringContainsString('Laravel', $html);
    }

    public function test_home_html_json_ld_reflects_the_english_session_locale(): void
    {
        Profile::create([
            'name' => 'Martín',
            'surname' => 'Pared Baez',
            'avatar' => 'avatar.webp',
            'email_contact' => 'paredbaez.martin@gmail.com',
            'description_es' => 'Desarrollador Full Stack Senior.',
            'description_en' => 'Senior Full Stack Developer.',
            'specialty_es' => 'Desarrollador Full Stack Senior',
            'specialty_en' => 'Senior Full Stack Developer',
        ]);

        Skill::create(['name' => 'Laravel', 'percentage' => 90]);

        $this->get('/locale/en');

        $html = $this->get('/')->getContent();

        $this->assertStringContainsString('"jobTitle": "Senior Full Stack Developer"', $html);
        $this->assertStringContainsString('"description": "Senior Full Stack Developer."', $html);
        // The dynamic OG locale mirrors the session (title/description copy
        // itself stays Spanish-only — the legacy `SEOConfig` never had an
        // English variant for page metadata, see PR9's apply-progress).
        $this->assertStringContainsString('property="og:locale" content="en_US"', $html);
    }

    public function test_home_html_title_and_meta_description_match_seo_config_for_the_home_page(): void
    {
        $seoHome = config('seo.pages.home');

        $html = $this->get('/')->getContent();

        $this->assertStringContainsString('<title inertia>'.$seoHome['title'].'</title>', $html);
        $this->assertStringContainsString(
            '<meta name="description" content="'.$seoHome['description'].'">',
            $html
        );
    }

    public function test_home_html_always_renders_the_dark_theme_with_no_toggle_script(): void
    {
        // The owner removed the light/dark toggle (2026-08-22): the site is
        // dark-only now, hardcoded straight on <html> — no blocking
        // pre-paint script is needed anymore since there's no
        // localStorage-driven choice left to race against first paint.
        $html = $this->get('/')->getContent();

        $this->assertStringContainsString('data-theme="baubyte-dark"', $html);
        $this->assertStringNotContainsString("localStorage.getItem('theme')", $html);
    }
}
