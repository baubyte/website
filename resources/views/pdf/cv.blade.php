{{-- Two-column table layout: dompdf 3.x has no flexbox/grid support, only tables/floats. --}}
@php
    $locale = $locale ?? 'es';
    $labels = $locale === 'en'
        ? ['contact' => 'Contact', 'summary' => 'Summary', 'skills' => 'Skills', 'experience' => 'Experience', 'education' => 'Education', 'language' => 'Language', 'links' => 'Professional Profiles']
        : ['contact' => 'Contacto', 'summary' => 'Perfil Profesional', 'skills' => 'Habilidades', 'experience' => 'Experiencia', 'education' => 'Formación', 'language' => 'Idioma', 'links' => 'Perfiles Profesionales'];

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
        margin: 2.2cm 2cm;
    }

    * {
        margin: 0;
        padding: 0;
        box-sizing: border-box;
    }

    body {
        font-family: 'DejaVu Sans', sans-serif;
        font-size: 10px;
        line-height: 1.45;
        color: #1f2937;
    }

    table.layout {
        width: 100%;
        border-collapse: collapse;
    }

    td.sidebar {
        width: 32%;
        vertical-align: top;
        padding-right: 18px;
    }

    td.main {
        width: 68%;
        vertical-align: top;
    }

    h1 {
        font-size: 24px;
        color: #1f2937;
        margin-bottom: 4px;
    }

    .specialty {
        font-size: 11px;
        color: #6b7280;
        margin-bottom: 16px;
    }

    .section {
        margin-bottom: 16px;
    }

    .section-title {
        font-size: 11px;
        font-weight: bold;
        text-transform: uppercase;
        letter-spacing: 1px;
        color: #3498db;
        border-bottom: 1px solid #3498db;
        padding-bottom: 3px;
        margin-bottom: 8px;
    }

    .contact-item,
    .link-item {
        font-size: 10px;
        margin-bottom: 4px;
    }

    .muted {
        color: #6b7280;
    }

    .skill-item {
        margin-bottom: 8px;
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

    .summary {
        font-size: 10px;
        line-height: 1.45;
    }

    .entry {
        margin-bottom: 12px;
        page-break-inside: avoid;
    }

    .entry-title {
        font-size: 11px;
        font-weight: bold;
    }

    .entry-dates {
        font-size: 10px;
        color: #6b7280;
    }
</style>

<body>
    <table class="layout">
        <tr>
            <td class="sidebar">
                <h1>{{ $fullName }}</h1>
                @if ($profile)
                    <div class="specialty">{{ $profile['specialty'] ?? '' }}</div>

                    <div class="section">
                        <div class="section-title">{{ $labels['contact'] }}</div>
                        @if (!empty($profile['email_contact']))
                            <div class="contact-item">{{ $profile['email_contact'] }}</div>
                        @endif
                        @if (!empty($profile['language']))
                            <div class="contact-item muted">{{ $labels['language'] }}: {{ $profile['language'] }}</div>
                        @endif
                    </div>

                    @if (!empty($profile['github_url']) || !empty($profile['linkedin_url']))
                        <div class="section">
                            <div class="section-title">{{ $labels['links'] }}</div>
                            @if (!empty($profile['github_url']))
                                <div class="link-item">GitHub: {{ $profile['github_url'] }}</div>
                            @endif
                            @if (!empty($profile['linkedin_url']))
                                <div class="link-item">LinkedIn: {{ $profile['linkedin_url'] }}</div>
                            @endif
                        </div>
                    @endif
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
            </td>

            <td class="main">
                @if ($profile && !empty($profile['description']))
                    <div class="section">
                        <div class="section-title">{{ $labels['summary'] }}</div>
                        <p class="summary">{{ $profile['description'] }}</p>
                    </div>
                @endif

                <div class="section">
                    <div class="section-title">{{ $labels['experience'] }}</div>
                    @foreach ($experiences as $experience)
                        <div class="entry">
                            <div class="entry-title">{{ $experience['company'] }} &mdash; {{ $experience['specialty'] }}</div>
                            <div class="entry-dates">
                                {{ \Illuminate\Support\Carbon::parse($experience['start_date'])->format('m/Y') }}
                                &mdash;
                                {{ $experience['end_date'] ? \Illuminate\Support\Carbon::parse($experience['end_date'])->format('m/Y') : 'Present' }}
                            </div>
                            <p>{{ $experience['description'] }}</p>
                        </div>
                    @endforeach
                </div>

                <div class="section">
                    <div class="section-title">{{ $labels['education'] }}</div>
                    @foreach ($studies as $study)
                        <div class="entry">
                            <div class="entry-title">{{ $study['entity'] }} &mdash; {{ $study['title'] }}</div>
                            <div class="entry-dates">
                                {{ \Illuminate\Support\Carbon::parse($study['start_date'])->format('m/Y') }}
                                &mdash;
                                {{ $study['end_date'] ? \Illuminate\Support\Carbon::parse($study['end_date'])->format('m/Y') : 'Present' }}
                            </div>
                            <p>{{ $study['description'] }}</p>
                        </div>
                    @endforeach
                </div>
            </td>
        </tr>
    </table>
</body>

</html>
