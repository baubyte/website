{{-- Two-column float layout: dompdf 3.x has no flexbox/grid, and its <table> pagination emits blank leading pages here. --}}
<!DOCTYPE html>
<html lang="{{ $locale }}">

<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <title>{{ $fullName }} - Curriculum Vitae</title>
    <meta name="author" content="{{ $fullName }}">
    <meta name="description" content="Curriculum Vitae">
    <meta name="keywords" content="CV, resume, {{ $fullName }}">
    <meta name="generator" content="{{ config('app.name') }}">
</head>

<style type="text/css">
    @page {
        size: A4;
        margin: 12mm 14mm 14mm 14mm;
    }

    * {
        margin: 0;
        padding: 0;
        box-sizing: border-box;
        word-wrap: break-word;
        overflow-wrap: break-word;
    }

    body {
        font-family: 'DejaVu Sans', sans-serif;
        font-size: 11px;
        line-height: 1.5;
        color: #2b3226;
    }

    .layout {
        position: relative;
        width: 100%;
    }

    .sidebar {
        position: absolute;
        top: 0;
        left: 0;
        width: 32%;
        background: #f2f5ef;
        padding: 16px 14px;
        border-radius: 6px;
    }

    .main {
        margin-left: 35%;
        width: 65%;
    }

    .avatar {
        display: block;
        width: 84px;
        height: 84px;
        border-radius: 50%;
        object-fit: cover;
        margin-bottom: 12px;
    }

    h1 {
        font-size: 22px;
        font-weight: bold;
        color: #1c2018;
        line-height: 1.2;
        margin-bottom: 6px;
    }

    .accent-rule {
        width: 34px;
        height: 3px;
        background: #d9a441;
        margin-bottom: 10px;
    }

    .specialty {
        font-size: 12px;
        font-weight: bold;
        color: #5c7a55;
        margin-bottom: 18px;
    }

    .section {
        margin-bottom: 18px;
    }

    .section-title {
        font-size: 11px;
        font-weight: bold;
        text-transform: uppercase;
        letter-spacing: 1.2px;
        color: #5c7a55;
        margin-bottom: 8px;
        break-after: avoid;
        page-break-after: avoid;
    }

    .main .section-title {
        border-bottom: 1.5px solid #d9a441;
        padding-bottom: 3px;
    }

    .contact-item,
    .link-item {
        font-size: 10.5px;
        margin-bottom: 5px;
        word-wrap: break-word;
    }

    .muted {
        color: #6b7563;
    }

    .skill-item {
        margin-bottom: 8px;
        break-inside: avoid;
        page-break-inside: avoid;
    }

    .skill-name {
        font-size: 10px;
        font-weight: bold;
        color: #2b3226;
        margin-bottom: 3px;
    }

    .skill-bar {
        width: 100%;
        height: 5px;
        background: #dde3d7;
        border-radius: 3px;
    }

    .skill-progress {
        height: 100%;
        background: #8fb389;
        border-radius: 3px;
    }

    .summary {
        font-size: 11px;
        line-height: 1.55;
        color: #3a4235;
    }

    .entry {
        margin-bottom: 14px;
        break-inside: avoid;
        page-break-inside: avoid;
    }

    .entry-title {
        font-size: 12.5px;
        font-weight: bold;
        color: #1c2018;
        margin-bottom: 2px;
    }

    .entry-dates {
        display: inline-block;
        font-size: 9.5px;
        font-weight: bold;
        letter-spacing: 0.4px;
        color: #5c7a55;
        background: #eaf0e6;
        border-radius: 3px;
        padding: 2px 7px;
        margin-bottom: 4px;
    }

    .entry p {
        font-size: 10.5px;
        color: #3a4235;
        line-height: 1.45;
    }
</style>

<body>
    <div class="layout">
        <div class="sidebar">
            @if ($avatarPath)
                <img class="avatar" src="{{ $avatarPath }}" alt="{{ $fullName }}" />
            @endif
            <h1>{{ $fullName }}</h1>
            <div class="accent-rule"></div>
            @if ($profile)
                <div class="specialty">{{ $profile['specialty'] ?? '' }}</div>

                <div class="section">
                    <div class="section-title">{{ __('cv.contact') }}</div>
                    @if (!empty($profile['email_contact']))
                        <div class="contact-item">{{ $profile['email_contact'] }}</div>
                    @endif
                    @if (!empty($profile['language']))
                        <div class="contact-item muted">{{ __('cv.language') }}: {{ $profile['language'] }}</div>
                    @endif
                </div>

                @if (!empty($profile['github_url']) || !empty($profile['linkedin_url']))
                    <div class="section">
                        <div class="section-title">{{ __('cv.links') }}</div>
                        @if (!empty($webUrl))
                            <div class="link-item">Website: <a href="{{ $webUrl }}" class="muted" target="_blank">{{ $webUrl }}</a></div>
                        @endif
                        @if (!empty($profile['github_url']))
                            <div class="link-item">GitHub: <a href="{{ $profile['github_url'] }}" class="muted" target="_blank">{{ $profile['github_url'] }}</a></div>
                        @endif
                        @if (!empty($profile['linkedin_url']))
                            <div class="link-item">LinkedIn: <a href="{{ $profile['linkedin_url'] }}" class="muted" target="_blank">{{ $profile['linkedin_url'] }}</a></div>
                        @endif
                    </div>
                @endif
            @endif

            <div class="section">
                <div class="section-title">{{ __('cv.skills') }}</div>
                @foreach ($skills as $skill)
                    <div class="skill-item">
                        <div class="skill-name">{{ $skill->name }} &middot; {{ $skill->percentage }}%</div>
                        <div class="skill-bar">
                            <div class="skill-progress" style="width: {{ $skill->percentage }}%;"></div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>

        <div class="main">
            @if ($profile && !empty($profile['description']))
                <div class="section">
                    <div class="section-title">{{ __('cv.summary') }}</div>
                    <p class="summary">{{ $profile['description'] }}</p>
                </div>
            @endif

            <div class="section">
                <div class="section-title">{{ __('cv.experience') }}</div>
                @foreach ($experiences as $experience)
                    <div class="entry">
                        <div class="entry-title">{{ $experience['company'] }} &mdash; {{ $experience['specialty'] }}</div>
                        <div class="entry-dates">{{ $experience['date_range'] }}</div>
                        <p>{{ $experience['description'] }}</p>
                    </div>
                @endforeach
            </div>

            <div class="section">
                <div class="section-title">{{ __('cv.education') }}</div>
                @foreach ($studies as $study)
                    <div class="entry">
                        <div class="entry-title">{{ $study['entity'] }} &mdash; {{ $study['title'] }}</div>
                        <div class="entry-dates">{{ $study['date_range'] }}</div>
                        <p>{{ $study['description'] }}</p>
                    </div>
                @endforeach
            </div>
        </div>
    </div>
</body>

</html>
