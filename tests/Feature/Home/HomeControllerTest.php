<?php

namespace Tests\Feature\Home;

use App\Models\Experience;
use App\Models\Profile;
use App\Models\Skill;
use App\Models\Study;
use Carbon\Carbon;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Inertia\Testing\AssertableInertia as Assert;
use Tests\TestCase;

class HomeControllerTest extends TestCase
{
    use RefreshDatabase;

    public function test_home_route_renders_the_home_inertia_component_with_expected_props(): void
    {
        $profile = Profile::create([
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

        $skill = Skill::create(['name' => 'Laravel', 'percentage' => 90]);

        $experience = Experience::create([
            'company' => 'Baubyte',
            'specialty_es' => 'Desarrollo Full Stack',
            'specialty_en' => 'Full Stack Development',
            'description_es' => 'Desarrollo de aplicaciones web.',
            'description_en' => 'Web application development.',
            'start_date' => '2020-01-01',
            'end_date' => null,
        ]);

        $study = Study::create([
            'entity' => 'UTN',
            'title_es' => 'Ingeniería en Sistemas',
            'title_en' => 'Systems Engineering',
            'description_es' => 'Carrera de grado.',
            'description_en' => 'Undergraduate degree.',
            'start_date' => '2015-01-01',
            'end_date' => '2020-12-31',
        ]);

        $response = $this->get('/');

        $response->assertOk();
        $response->assertInertia(fn (Assert $page) => $page
            // `Home.svelte` does not exist yet (PR8) — this unit is
            // backend-only, so skip Inertia's on-disk component file check.
            ->component('Home', shouldExist: false)
            ->has('profile')
            ->where('profile.id', $profile->id)
            ->where('profile.name', 'Martín')
            // Default locale is `es` (session-based, see PR9's
            // `App\Support\Locale\Locale`): props arrive already resolved
            // to a single language — `description`/`specialty`/`language`,
            // never the raw `_es`/`_en` pair — so Svelte components never
            // need to know about locale suffixes.
            ->where('profile.description', 'Desarrollador Full Stack Senior.')
            ->where('profile.specialty', 'Desarrollador Full Stack Senior')
            ->missing('profile.description_es')
            ->missing('profile.description_en')
            ->has('skills', 1)
            ->where('skills.0.id', $skill->id)
            ->has('experiences', 1)
            ->where('experiences.0.id', $experience->id)
            ->where('experiences.0.specialty', 'Desarrollo Full Stack')
            ->has('studies', 1)
            ->where('studies.0.id', $study->id)
            ->where('studies.0.title', 'Ingeniería en Sistemas')
            // Computed relative to "now" rather than a hardcoded number so
            // this assertion never silently drifts wrong as real time
            // passes — same formula the controller itself uses.
            ->where('yearsOfExperience', (int) Carbon::parse('2020-01-01')->diffInYears(now()))
        );
    }

    public function test_years_of_experience_is_computed_from_the_earliest_experience_start_date_not_hardcoded(): void
    {
        // Real bug the owner caught: the Hero badge showed a hardcoded
        // "+10 años de experiencia" string that had no connection to the
        // actual data and was already wrong once real experience rows
        // existed. This must be computed from the DB, not authored text.
        Experience::create([
            'company' => 'Older Co',
            'specialty_es' => 'Dev',
            'specialty_en' => 'Dev',
            'description_es' => '',
            'description_en' => '',
            'start_date' => now()->subYears(8)->toDateString(),
            'end_date' => now()->subYears(6)->toDateString(),
        ]);
        Experience::create([
            'company' => 'Current Co',
            'specialty_es' => 'Dev',
            'specialty_en' => 'Dev',
            'description_es' => '',
            'description_en' => '',
            'start_date' => now()->subYears(3)->toDateString(),
            'end_date' => null,
        ]);

        $response = $this->get('/');

        $response->assertInertia(fn (Assert $page) => $page
            ->component('Home', shouldExist: false)
            // Must count from the EARLIEST experience (8 years ago), not
            // the most recent one (3 years ago).
            ->where('yearsOfExperience', 8)
        );
    }

    public function test_years_of_experience_is_null_when_there_are_no_experiences(): void
    {
        $response = $this->get('/');

        $response->assertInertia(fn (Assert $page) => $page
            ->component('Home', shouldExist: false)
            ->where('yearsOfExperience', null)
        );
    }

    public function test_home_route_resolves_props_to_english_when_the_session_locale_is_english(): void
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

        Experience::create([
            'company' => 'Baubyte',
            'specialty_es' => 'Desarrollo Full Stack',
            'specialty_en' => 'Full Stack Development',
            'description_es' => 'Desarrollo de aplicaciones web.',
            'description_en' => 'Web application development.',
            'start_date' => '2020-01-01',
            'end_date' => null,
        ]);

        // `studies.title_en`/`experiences.specialty_en` are NOT NULL at the
        // schema level (verified in `database/migrations/*_create_studies_
        // table.php` / `*_create_experiences_table.php` — a factual
        // correction to this task's premise that every `_en` column is
        // nullable; only the `description_en` columns and `profiles.
        // specialty_en`/`description_en` actually are). `description_en` is
        // left `null` here on purpose to exercise the real fallback path.
        Study::create([
            'entity' => 'UTN',
            'title_es' => 'Ingeniería en Sistemas',
            'title_en' => 'Systems Engineering',
            'description_es' => 'Carrera de grado.',
            'description_en' => null,
            'start_date' => '2015-01-01',
            'end_date' => '2020-12-31',
        ]);

        $this->get('/locale/en');

        $response = $this->get('/');

        $response->assertOk();
        $response->assertInertia(fn (Assert $page) => $page
            ->component('Home', shouldExist: false)
            ->where('profile.description', 'Senior Full Stack Developer.')
            ->where('profile.specialty', 'Senior Full Stack Developer')
            ->where('experiences.0.specialty', 'Full Stack Development')
            ->where('studies.0.title', 'Systems Engineering')
            // Fallback to Spanish when the real record has no `_en` value.
            ->where('studies.0.description', 'Carrera de grado.')
        );
    }

    public function test_home_route_renders_with_null_profile_when_none_exists(): void
    {
        $response = $this->get('/');

        $response->assertOk();
        $response->assertInertia(fn (Assert $page) => $page
            ->component('Home', shouldExist: false)
            ->where('profile', null)
            ->has('skills', 0)
            ->has('experiences', 0)
            ->has('studies', 0)
        );
    }
}
