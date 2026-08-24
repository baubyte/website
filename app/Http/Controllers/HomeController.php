<?php

namespace App\Http\Controllers;

use App\Models\Experience;
use App\Models\Profile;
use App\Models\Skill;
use App\Models\Study;
use App\Support\Locale\Locale;
use Carbon\Carbon;
use Inertia\Inertia;
use Inertia\Response;

class HomeController extends Controller
{
    public function index(): Response
    {
        $locale = Locale::current();
        $profile = Profile::first();

        return Inertia::render('Home', [
            // `profile`/`experiences`/`studies` arrive already resolved to
            // the session locale (see `ResolvesLocalizedFields`) — Svelte
            // components never see the raw `_es`/`_en` field pair.
            'profile' => $profile?->toLocalizedArray($locale),
            'skills' => Skill::orderBy('name')->get(),
            'experiences' => Experience::orderBy('start_date', 'desc')->get()
                ->map(fn (Experience $experience) => $experience->toLocalizedArray($locale))
                ->values(),
            'studies' => Study::orderBy('start_date', 'desc')->get()
                ->map(fn (Study $study) => $study->toLocalizedArray($locale))
                ->values(),
            // Real, computed from the earliest experience row — never a
            // hardcoded string, so it never goes stale as experiences are
            // added/edited from Filament.
            'yearsOfExperience' => $this->yearsOfExperience(),
        ]);
    }

    private function yearsOfExperience(): ?int
    {
        $earliestStart = Experience::min('start_date');

        if (! $earliestStart) {
            return null;
        }

        return (int) Carbon::parse($earliestStart)->diffInYears(now());
    }
}
