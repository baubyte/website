<?php

namespace Tests\Feature\Cv;

use App\Models\Experience;
use App\Models\Profile;
use App\Models\Skill;
use App\Models\Study;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

// Content/markup assertions render `pdf.cv` directly, rather than parsing the final compiled PDF binary.
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
        $this->assertStringContainsString('filename="cv.pdf"', $response->headers->get('Content-Disposition'));
    }

    public function test_download_cv_carries_profile_metadata_in_html_head(): void
    {
        $profile = $this->seedRealData();

        $html = $this->renderCvView($profile);

        $this->assertStringContainsString('<title>Martín Pared Baez - Curriculum Vitae</title>', $html);
        $this->assertStringContainsString('<meta name="author" content="Martín Pared Baez">', $html);
        $this->assertStringContainsString('<meta name="description" content="Curriculum Vitae">', $html);
    }

    public function test_download_cv_includes_the_avatar_when_the_file_exists(): void
    {
        Storage::fake('public');
        Storage::disk('public')->put('avatar.webp', 'fake-image-bytes');

        $this->seedRealData();

        $body = str($this->renderCvView(Profile::first()))->after('<body>')->toString();

        $this->assertStringContainsString('class="avatar"', $body);
        $this->assertStringContainsString(Storage::disk('public')->path('avatar.webp'), $body);
    }

    public function test_download_cv_omits_the_avatar_when_the_file_is_missing(): void
    {
        Storage::fake('public');

        $this->seedRealData();

        $body = str($this->renderCvView(Profile::first()))->after('<body>')->toString();

        $this->assertStringNotContainsString('class="avatar"', $body);
    }

    public function test_download_cv_content_has_no_duplicated_contact_block(): void
    {
        $profile = $this->seedRealData();
        $body = str($this->renderCvView($profile))->after('<body>')->toString();

        $this->assertSame(1, substr_count($body, 'Martín Pared Baez'));
        $this->assertSame(2, substr_count($body, $profile->github_url));
        $this->assertSame(2, substr_count($body, $profile->linkedin_url));

        foreach (['Contacto', 'Perfiles Profesionales', 'Habilidades', 'Experiencia', 'Formación'] as $label) {
            $this->assertSame(1, substr_count($body, $label), "Expected label [{$label}] to appear exactly once.");
        }
    }

    public function test_download_cv_entries_keep_page_break_avoid_structure(): void
    {
        $profile = $this->seedRealData();
        Experience::create([
            'company' => 'Second Co',
            'specialty_es' => 'Backend',
            'specialty_en' => 'Backend',
            'description_es' => str_repeat('Descripción extensa. ', 60),
            'description_en' => str_repeat('Long description. ', 60),
            'start_date' => '2018-01-01',
            'end_date' => '2019-12-31',
        ]);

        $html = $this->renderCvView($profile);

        $this->assertStringContainsString('page-break-inside: avoid', $html);
        $this->assertSame(3, substr_count($html, 'class="entry"'));
    }



    private function renderCvView(?Profile $profile): string
    {
        return view('pdf.cv', $this->cvViewData($profile))->render();
    }

    /**
     * @return array<string, mixed>
     */
    private function cvViewData(?Profile $profile): array
    {
        // Direct view() calls below bypass the HTTP kernel, so
        // SetApplicationLocale never runs — set it explicitly to match what
        // a real `/download-cv` request gets, keeping __('cv.*') in Spanish.
        app()->setLocale('es');

        $fullName = $profile ? trim("{$profile->name} {$profile->surname}") : config('app.name');

        $avatarPath = $profile?->avatar && Storage::disk('public')->exists($profile->avatar)
            ? Storage::disk('public')->path($profile->avatar)
            : null;

        return [
            'locale' => 'es',
            'fullName' => $fullName,
            'avatarPath' => $avatarPath,
            'profile' => $profile?->toLocalizedArray('es'),
            'skills' => Skill::orderBy('name')->get(),
            'experiences' => Experience::orderBy('start_date', 'desc')->get()
                ->map(fn (Experience $experience) => $this->withDateRange($experience->toLocalizedArray('es'))),
            'studies' => Study::orderBy('start_date', 'desc')->get()
                ->map(fn (Study $study) => $this->withDateRange($study->toLocalizedArray('es'))),
        ];
    }

    /**
     * @param  array<string, mixed>  $entry
     * @return array<string, mixed>
     */
    private function withDateRange(array $entry): array
    {
        $end = $entry['end_date']
            ? Carbon::parse($entry['end_date'])->format('m/Y')
            : __('cv.present');

        $entry['date_range'] = Carbon::parse($entry['start_date'])->format('m/Y')." – {$end}";

        return $entry;
    }

}
