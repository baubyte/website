{{--
    SEO / JSON-LD partial.

    Rendered from Blade (not from Svelte's `<svelte:head>`) so it always
    reaches the browser in the initial HTML response, independent of the
    Inertia/Svelte client mounting successfully. This is a deliberate design
    decision: if a later SSR process (see PR10) ever falls back to
    client-side rendering, the structured data and base meta tags below
    still ship with the very first response.

    Per-page `<title>`/OG overrides coming from an Inertia page component
    (via `@inertiaHead`) may layer on top of this once Svelte pages exist,
    but the JSON-LD structured data below is always Blade-rendered.
--}}
@php
    $seoPage = config('seo.pages.'.($seoPageKey ?? 'home'), config('seo.pages.home'));
    $keywords = $seoPage['keywords'] ?: implode(', ', config('seo.primary_keywords', []));
    $defaultOg = config('seo.default_og', []);
@endphp

<title inertia>{{ $seoPage['title'] }}</title>
<meta name="description" content="{{ $seoPage['description'] }}">
<meta name="keywords" content="{{ $keywords }}">
<link rel="canonical" href="{{ url($seoPage['canonical']) }}">

{{-- Open Graph --}}
<meta property="og:type" content="{{ $defaultOg['type'] ?? 'website' }}">
<meta property="og:locale" content="{{ $defaultOg['locale'] ?? 'es_ES' }}">
<meta property="og:site_name" content="{{ $defaultOg['site_name'] ?? config('app.name') }}">
<meta property="og:title" content="{{ $seoPage['title'] }}">
<meta property="og:description" content="{{ $seoPage['description'] }}">
<meta property="og:url" content="{{ url()->current() }}">
@if (!empty($defaultOg['image']))
    <meta property="og:image" content="{{ url($defaultOg['image']) }}">
    <meta property="og:image:width" content="{{ $defaultOg['image_width'] ?? '' }}">
    <meta property="og:image:height" content="{{ $defaultOg['image_height'] ?? '' }}">
@endif

{{-- Twitter Card --}}
<meta name="twitter:card" content="summary_large_image">
<meta name="twitter:title" content="{{ $seoPage['title'] }}">
<meta name="twitter:description" content="{{ $seoPage['description'] }}">

{{-- Schema.org / JSON-LD (always Blade-rendered, see docblock above) --}}
{!! \App\Support\Seo\PersonSchema::toJsonLdScript(
    \App\Support\Seo\PersonSchema::build(
        \App\Models\Profile::first(),
        \App\Models\Skill::orderBy('name')->get()
    )
) !!}
