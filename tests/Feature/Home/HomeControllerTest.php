<?php

namespace Tests\Feature\Home;

use App\Models\Experience;
use App\Models\Profile;
use App\Models\Skill;
use App\Models\Study;
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
            ->has('skills', 1)
            ->where('skills.0.id', $skill->id)
            ->has('experiences', 1)
            ->where('experiences.0.id', $experience->id)
            ->has('studies', 1)
            ->where('studies.0.id', $study->id)
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
