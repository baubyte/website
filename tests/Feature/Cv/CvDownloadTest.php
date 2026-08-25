<?php

namespace Tests\Feature\Cv;

use App\Models\Experience;
use App\Models\Profile;
use App\Models\Skill;
use App\Models\Study;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Carbon;
use Tests\TestCase;

// Content/markup assertions render `pdf.cv` directly, since dompdf compresses the final PDF's text streams.
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
        $this->assertStringContainsString('attachment; filename=cv.pdf', $response->headers->get('Content-Disposition'));
    }

    public function test_download_cv_pdf_info_dict_carries_profile_metadata(): void
    {
        $this->seedRealData();

        $response = $this->get('/download-cv');
        $pdf = $response->getContent();

        $this->assertSame('Martín Pared Baez - Curriculum Vitae', $this->extractPdfInfoValue($pdf, 'Title'));
        $this->assertSame('Martín Pared Baez', $this->extractPdfInfoValue($pdf, 'Author'));
        $this->assertSame('Curriculum Vitae', $this->extractPdfInfoValue($pdf, 'Subject'));
    }

    public function test_download_cv_content_has_no_duplicated_contact_block(): void
    {
        $profile = $this->seedRealData();
        $body = str($this->renderCvView($profile))->after('<body>')->toString();

        $this->assertSame(1, substr_count($body, 'Martín Pared Baez'));
        $this->assertSame(1, substr_count($body, $profile->github_url));
        $this->assertSame(1, substr_count($body, $profile->linkedin_url));

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

    public function test_download_cv_has_no_leading_blank_pages(): void
    {
        $profile = $this->seedRealData();

        $pdf = app('dompdf.wrapper')->loadView('pdf.cv', $this->cvViewData($profile));
        $pdf->render();

        $this->assertSame(1, $pdf->getDomPDF()->getCanvas()->get_page_count());
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
        // Direct view()/dompdf.wrapper calls below bypass the HTTP kernel, so
        // SetApplicationLocale never runs — set it explicitly to match what
        // a real `/download-cv` request gets, keeping __('cv.*') in Spanish.
        app()->setLocale('es');

        $fullName = $profile ? trim("{$profile->name} {$profile->surname}") : config('app.name');

        return [
            'locale' => 'es',
            'fullName' => $fullName,
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

    private function extractPdfInfoValue(string $pdf, string $label): ?string
    {
        if (! preg_match('/\/'.preg_quote($label, '/').'\s*\((.*?)\)/s', $pdf, $matches)) {
            return null;
        }

        return mb_convert_encoding(substr($matches[1], 2), 'UTF-8', 'UTF-16BE');
    }
}
