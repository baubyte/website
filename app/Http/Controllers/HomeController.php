<?php

namespace App\Http\Controllers;

use App\Models\Experience;
use App\Models\Profile;
use App\Models\Skill;
use App\Models\Study;
use App\Support\Icons\IconCatalog;
use App\Support\Locale\Locale;
use Carbon\Carbon;
use Illuminate\Support\Facades\Cache;
use Inertia\Inertia;
use Inertia\Response;

class HomeController extends Controller
{
    public function index(): Response
    {
        if (! session()->has('has_visited')) {
            if (! Cache::has('site_visits')) {
                Cache::forever('site_visits', 0);
            }
            Cache::increment('site_visits');
            session()->put('has_visited', true);
        }

        $locale = Locale::current();
        $profile = Profile::first();

        $profileData = $profile?->toLocalizedArray($locale);
        $skillsData = Skill::with('skillCategory')->orderBy('name')->get()->map(fn (Skill $skill) => [
            ...$skill->toArray(),
            'category' => $skill->skillCategory ? $skill->skillCategory->toLocalizedArray($locale) : null,
            'icon_data' => IconCatalog::resolve($skill->icon),
        ])->values();

        $experiencesData = Experience::orderBy('start_date', 'desc')->get()
            ->map(fn (Experience $experience) => $experience->toLocalizedArray($locale))
            ->values();

        $studiesData = Study::orderBy('start_date', 'desc')->get()
            ->map(fn (Study $study) => $study->toLocalizedArray($locale))
            ->values();

        $yearsOfExperience = $this->yearsOfExperience();

        return Inertia::render('Home', [
            'profile' => $profileData,
            'skills' => $skillsData,
            'experiences' => $experiencesData,
            'studies' => $studiesData,
            'yearsOfExperience' => $yearsOfExperience,
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
