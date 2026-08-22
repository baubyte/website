<?php

namespace Tests\Feature\Cv;

use App\Models\Experience;
use App\Models\Profile;
use App\Models\Skill;
use App\Models\Study;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

/**
 * `GET /download-cv` — PDF generation ported from the legacy CodeIgniter
 * app's `app/Views/Front/pdf/cv.php`, rendered from real Profile/Skill/
 * Experience/Study data in the current session locale.
 *
 * Per the task's own allowance, this does not use a sophisticated PDF text
 * parser: it asserts `Content-Type: application/pdf`, a real (non-trivial)
 * byte size, and the real profile's name via the `Content-Disposition`
 * filename, which is derived directly from the live `Profile` row.
 */
class CvDownloadTest extends TestCase
{
    use RefreshDatabase;

    private function seedRealData(): Profile
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
        ]);

        Skill::create(['name' => 'Laravel', 'percentage' => 90]);

        Experience::create([
            'company' => 'Baubyte',
            'specialty_es' => 'Desarrollo Full Stack',
            'specialty_en' => 'Full Stack Development',
            'description_es' => 'Desarrollo de aplicaciones web.',
            'description_en' => 'Web application development.',
            'start_date' => '2020-01-01',
            'end_date' => null,
        ]);

        Study::create([
            'entity' => 'UTN',
            'title_es' => 'Ingeniería en Sistemas',
            'title_en' => 'Systems Engineering',
            'description_es' => 'Carrera de grado.',
            'description_en' => 'Undergraduate degree.',
            'start_date' => '2015-01-01',
            'end_date' => '2020-12-31',
        ]);

        return $profile;
    }

    public function test_download_cv_responds_with_a_real_pdf_named_after_the_profile(): void
    {
        $this->seedRealData();

        $response = $this->get('/download-cv');

        $response->assertOk();
        $response->assertHeader('Content-Type', 'application/pdf');

        $contentDisposition = $response->headers->get('Content-Disposition');
        $this->assertStringContainsString('martin-pared-baez', strtolower($contentDisposition));

        // A real rendered PDF (fonts, layout, embedded content) is always
        // well beyond a trivial/empty-stub byte size.
        $this->assertGreaterThan(2000, strlen($response->getContent()));
        $this->assertStringStartsWith('%PDF-', $response->getContent());
    }

    public function test_download_cv_without_a_profile_still_responds_with_a_pdf(): void
    {
        $response = $this->get('/download-cv');

        $response->assertOk();
        $response->assertHeader('Content-Type', 'application/pdf');
    }
}
