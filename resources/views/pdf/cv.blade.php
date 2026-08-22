{{--
    CV PDF, ported from the legacy CodeIgniter app's
    `app/Views/Front/pdf/cv.php` to Blade + dompdf (`barryvdh/laravel-dompdf`).

    `$profile`/`$experiences`/`$studies` already arrive resolved to a single
    language (see `ResolvesLocalizedFields::toLocalizedArray()`), so this
    view never touches `_es`/`_en` suffixes directly — only the small
    section-heading labels below are locale-switched, since this project has
    no full Laravel localization file set (`resources/lang`) yet; that stays
    out of this unit's scope.
--}}
@php
    $locale = $locale ?? 'es';
    $labels = $locale === 'en'
        ? ['skills' => 'Skills', 'experience' => 'Experience', 'education' => 'Education', 'language' => 'Language', 'links' => 'Professional Profiles']
        : ['skills' => 'Habilidades', 'experience' => 'Experiencia', 'education' => 'Formación', 'language' => 'Idioma', 'links' => 'Perfiles Profesionales'];

    $fullName = $profile ? trim(($profile['name'] ?? '').' '.($profile['surname'] ?? '')) : config('app.name');
@endphp
<!DOCTYPE html>
<html lang="{{ $locale }}">

<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <title>{{ $fullName }} - Curriculum Vitae</title>
</head>

<style type="text/css">
    @page {
        margin: 3cm;
    }

    * {
        margin: 0;
        padding: 0;
        box-sizing: border-box;
    }

    body {
        font-family: 'DejaVu Sans', sans-serif;
        font-size: 12px;
        line-height: 1.6;
        color: #2c3e50;
    }

    .header {
        background: #2c3e50;
        color: #fff;
        padding: 24px;
        margin-bottom: 20px;
    }

    .header h1 {
        font-size: 26px;
    }

    .header .title {
        font-size: 15px;
        opacity: 0.9;
    }

    .contact-item {
        display: inline-block;
        margin-right: 16px;
        font-size: 11px;
    }

    .description {
        background: #f8f9fa;
        padding: 16px;
        border-left: 4px solid #3498db;
        margin-bottom: 20px;
    }

    .section {
        margin-bottom: 24px;
    }

    .section-title {
        font-size: 15px;
        font-weight: bold;
        border-bottom: 2px solid #3498db;
        padding-bottom: 4px;
        margin-bottom: 12px;
    }

    .skill-item,
    .experience-item,
    .education-item {
        margin-bottom: 10px;
    }

    .skill-bar {
        width: 100%;
        height: 4px;
        background: #ecf0f1;
    }

    .skill-progress {
        height: 100%;
        background: #3498db;
    }
</style>

<body>
    <div class="header">
        <h1>{{ $fullName }}</h1>
        @if ($profile)
            <div class="title">{{ $profile['specialty'] ?? '' }}</div>
            <div>
                <span class="contact-item">Email: {{ $profile['email_contact'] ?? '' }}</span>
                @if (!empty($profile['language']))
                    <span class="contact-item">{{ $labels['language'] }}: {{ $profile['language'] }}</span>
                @endif
                @if (!empty($profile['github_url']))
                    <span class="contact-item">GitHub: {{ $profile['github_url'] }}</span>
                @endif
                @if (!empty($profile['linkedin_url']))
                    <span class="contact-item">LinkedIn: {{ $profile['linkedin_url'] }}</span>
                @endif
            </div>
        @endif
    </div>

    @if ($profile && !empty($profile['description']))
        <div class="description">
            <p>{{ $profile['description'] }}</p>
        </div>
    @endif

    <div class="section">
        <div class="section-title">{{ $labels['skills'] }}</div>
        @foreach ($skills as $skill)
            <div class="skill-item">
                <div>{{ $skill->name }} &mdash; {{ $skill->percentage }}%</div>
                <div class="skill-bar">
                    <div class="skill-progress" style="width: {{ $skill->percentage }}%;"></div>
                </div>
            </div>
        @endforeach
    </div>

    <div class="section">
        <div class="section-title">{{ $labels['experience'] }}</div>
        @foreach ($experiences as $experience)
            <div class="experience-item">
                <strong>{{ $experience['company'] }}</strong> &mdash; {{ $experience['specialty'] }}<br>
                <small>
                    {{ \Illuminate\Support\Carbon::parse($experience['start_date'])->format('m/Y') }}
                    &mdash;
                    {{ $experience['end_date'] ? \Illuminate\Support\Carbon::parse($experience['end_date'])->format('m/Y') : 'Present' }}
                </small>
                <p>{{ $experience['description'] }}</p>
            </div>
        @endforeach
    </div>

    <div class="section">
        <div class="section-title">{{ $labels['education'] }}</div>
        @foreach ($studies as $study)
            <div class="education-item">
                <strong>{{ $study['entity'] }}</strong> &mdash; {{ $study['title'] }}<br>
                <small>
                    {{ \Illuminate\Support\Carbon::parse($study['start_date'])->format('m/Y') }}
                    &mdash;
                    {{ $study['end_date'] ? \Illuminate\Support\Carbon::parse($study['end_date'])->format('m/Y') : 'Present' }}
                </small>
                <p>{{ $study['description'] }}</p>
            </div>
        @endforeach
    </div>

    @if ($profile && (!empty($profile['github_url']) || !empty($profile['linkedin_url'])))
        <div class="section">
            <div class="section-title">{{ $labels['links'] }}</div>
            @if (!empty($profile['github_url']))
                <div>GitHub: {{ $profile['github_url'] }}</div>
            @endif
            @if (!empty($profile['linkedin_url']))
                <div>LinkedIn: {{ $profile['linkedin_url'] }}</div>
            @endif
        </div>
    @endif
</body>

</html>
