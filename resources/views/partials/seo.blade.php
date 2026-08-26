@php
    $seoPage = config('seo.pages.'.($seoPageKey ?? 'home'), config('seo.pages.home'));
    $keywords = $seoPage['keywords'] ?: implode(', ', config('seo.primary_keywords', []));
    $defaultOg = config('seo.default_og', []);
    $locale = \App\Support\Locale\Locale::current();
    $ogLocale = $locale === 'en' ? 'en_US' : ($defaultOg['locale'] ?? 'es_ES');
@endphp

<title inertia>{{ $seoPage['title'] }}</title>
<meta name="description" content="{{ $seoPage['description'] }}">
<meta name="keywords" content="{{ $keywords }}">
<link rel="canonical" href="{{ url($seoPage['canonical']) }}">

{{-- Open Graph --}}
<meta property="og:type" content="{{ $defaultOg['type'] ?? 'website' }}">
<meta property="og:locale" content="{{ $ogLocale }}">
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
        \App\Models\Skill::orderBy('name')->get(),
        $locale
    )
) !!}
