<?php

namespace App\Http\Controllers;

use App\Models\Experience;
use App\Models\Profile;
use App\Models\Skill;
use App\Models\Study;
use App\Support\Locale\Locale;
use Fruitcake\WeasyPrint\Facades\WeasyPrint;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Symfony\Component\HttpFoundation\Response;

/**
 * `GET /download-cv` — CV PDF generation, ported from the legacy
 * CodeIgniter app's `app/Views/Front/pdf/cv.php` to a Blade view
 * (`resources/views/pdf/cv.blade.php`), rendered from the real migrated
 * `Profile`/`Skill`/`Experience`/`Study` data in the current session
 * or query locale.
 */
class CvController extends Controller
{
    public function download(Request $request): Response
    {
        $requestedLocale = (string) $request->query('locale');
        $locale = Locale::isSupported($requestedLocale) ? $requestedLocale : app()->getLocale();

        app()->setLocale($locale);

        $profile = Profile::first();
        $fullName = $profile ? trim("{$profile->name} {$profile->surname}") : config('app.name');

        $pdf = WeasyPrint::loadView('pdf.cv', [
            'locale' => $locale,
            'fullName' => $fullName,
            'avatarPath' => $this->avatarPath($profile),
            'profile' => $profile?->toLocalizedArray($locale),
            'skills' => Skill::orderBy('name')->get(),
            'experiences' => Experience::orderBy('start_date', 'desc')->get()
                ->map(fn (Experience $experience) => $this->withDateRange($experience->toLocalizedArray($locale), $locale)),
            'studies' => Study::orderBy('start_date', 'desc')->get()
                ->map(fn (Study $study) => $this->withDateRange($study->toLocalizedArray($locale), $locale)),
            'webUrl' => config('app.url'),
        ]);

        $filename = $profile
            ? Str::slug(trim("{$profile->name} {$profile->surname}")).'-cv.pdf'
            : 'cv.pdf';

        return $pdf->download($filename);
    }

    /**
     * @param  array<string, mixed>  $entry
     * @return array<string, mixed>
     */
    private function withDateRange(array $entry, string $locale): array
    {
        $end = $entry['end_date']
            ? Carbon::parse($entry['end_date'])->format('m/Y')
            : __('cv.present', [], $locale);

        $entry['date_range'] = Carbon::parse($entry['start_date'])->format('m/Y')." – {$end}";

        return $entry;
    }

    /**
     * Resolve avatar path for PDF
     */
    private function avatarPath(?Profile $profile): ?string
    {
        if (! $profile?->avatar || ! Storage::disk('public')->exists($profile->avatar)) {
            return null;
        }

        return Storage::disk('public')->path($profile->avatar);
    }
}
