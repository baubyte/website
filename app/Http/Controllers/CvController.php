<?php

namespace App\Http\Controllers;

use App\Models\Experience;
use App\Models\Profile;
use App\Models\Skill;
use App\Models\Study;
use App\Support\Locale\Locale;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Carbon;
use Illuminate\Support\Str;
use Symfony\Component\HttpFoundation\Response;

/**
 * `GET /download-cv` — CV PDF generation, ported from the legacy
 * CodeIgniter app's `app/Views/Front/pdf/cv.php` to a Blade view
 * (`resources/views/pdf/cv.blade.php`), rendered from the real migrated
 * `Profile`/`Skill`/`Experience`/`Study` data in the current session
 * locale.
 */
class CvController extends Controller
{
    public function download(): Response
    {
        $locale = Locale::current();
        $profile = Profile::first();
        $fullName = $profile ? trim("{$profile->name} {$profile->surname}") : config('app.name');

        $pdf = Pdf::loadView('pdf.cv', [
            'locale' => $locale,
            'fullName' => $fullName,
            'profile' => $profile?->toLocalizedArray($locale),
            'skills' => Skill::orderBy('name')->get(),
            'experiences' => Experience::orderBy('start_date', 'desc')->get()
                ->map(fn (Experience $experience) => $this->withDateRange($experience->toLocalizedArray($locale))),
            'studies' => Study::orderBy('start_date', 'desc')->get()
                ->map(fn (Study $study) => $this->withDateRange($study->toLocalizedArray($locale))),
        ]);

        $filename = $profile
            ? Str::slug(trim("{$profile->name} {$profile->surname}")).'-cv.pdf'
            : 'cv.pdf';

        // addInfo() must run after render(): dompdf only persists it once `rendered` is set.
        $pdf->render();
        $pdf->getDomPDF()->addInfo('Title', "{$fullName} - Curriculum Vitae");
        $pdf->getDomPDF()->addInfo('Author', $fullName);
        $pdf->getDomPDF()->addInfo('Subject', 'Curriculum Vitae');
        $pdf->getDomPDF()->addInfo('Keywords', "CV, resume, {$fullName}");
        $pdf->getDomPDF()->addInfo('Creator', config('app.name'));

        return $pdf->download($filename);
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
