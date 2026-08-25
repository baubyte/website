{{-- Two-column float layout: dompdf 3.x has no flexbox/grid, and its <table> pagination emits blank leading pages here. --}}
<!DOCTYPE html>
<html lang="{{ $locale }}">

<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <title>{{ $fullName }} - Curriculum Vitae</title>
</head>

<style type="text/css">
    /*
     * @page margin-top is not reliably applied to floated content across
     * page breaks in dompdf 3.x — the floats (.sidebar/.main) started flush
     * against the physical page edge on second-and-later pages. Zeroing the
     * page margin and padding a non-floated wrapper instead makes the top
     * offset repeat correctly on every page, since dompdf paginates a
     * block-level container's own padding the same way normal text wraps.
     */
    @page {
        margin: 0;
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
        font-size: 9.5px;
        line-height: 1.55;
        color: #2b3226;
    }

    .layout {
        width: 100%;
        padding: 0 2cm 1.5cm;
    }

    .layout::after {
        content: '';
        display: block;
        clear: both;
    }

    /*
     * Fixed cm widths instead of percentages, and float:left+margin-left
     * for .main instead of float:right: dompdf 3.x's float:right anchors
     * to the page's own right edge instead of the .layout container's
     * padded content edge, letting .main's text overflow straight past
     * the printable page area with real (long) content. float:left with
     * an explicit margin-left doesn't have that failure mode.
     *
     * The top offset moved from .layout's own padding to padding-top on
     * each float directly: dompdf repeats a float's own padding on every
     * page it continues onto, but does not repeat an ancestor's padding.
     */
    .sidebar {
        float: left;
        width: 5.4cm;
        background: #f2f5ef;
        padding: 2.2cm 14px 14px;
        border-radius: 6px;
    }

    .main {
        float: left;
        width: 10.6cm;
        margin-left: 0.6cm;
        padding-top: 2.2cm;
    }

    .avatar {
        display: block;
        width: 76px;
        height: 76px;
        border-radius: 50%;
        object-fit: cover;
        margin-bottom: 12px;
    }

    h1 {
        font-size: 27px;
        font-weight: bold;
        color: #1c2018;
        line-height: 1.15;
        margin-bottom: 6px;
    }

    .accent-rule {
        width: 34px;
        height: 3px;
        background: #d9a441;
        margin-bottom: 10px;
    }

    .specialty {
        font-size: 11px;
        font-weight: bold;
        color: #5c7a55;
        margin-bottom: 22px;
    }

    .section {
        margin-bottom: 20px;
    }

    .section-title {
        font-size: 10px;
        font-weight: bold;
        text-transform: uppercase;
        letter-spacing: 1.5px;
        color: #5c7a55;
        margin-bottom: 10px;
    }

    .main .section-title {
        border-bottom: 1.5px solid #d9a441;
        padding-bottom: 4px;
    }

    .contact-item,
    .link-item {
        font-size: 9.5px;
        margin-bottom: 5px;
        word-wrap: break-word;
    }

    .muted {
        color: #6b7563;
    }

    .skill-item {
        margin-bottom: 10px;
    }

    .skill-name {
        font-size: 9.5px;
        font-weight: bold;
        color: #2b3226;
        margin-bottom: 4px;
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
        font-size: 9.5px;
        line-height: 1.6;
        color: #3a4235;
    }

    .entry {
        margin-bottom: 15px;
        page-break-inside: avoid;
    }

    .entry-title {
        font-size: 11.5px;
        font-weight: bold;
        color: #1c2018;
        margin-bottom: 2px;
    }

    .entry-dates {
        display: inline-block;
        font-size: 8.5px;
        font-weight: bold;
        letter-spacing: 0.5px;
        color: #5c7a55;
        background: #eaf0e6;
        border-radius: 3px;
        padding: 2px 7px;
        margin-bottom: 6px;
    }

    .entry p {
        color: #3a4235;
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
