<script>
    import FrontLayout from '../Layouts/FrontLayout.svelte';
    import Hero from '../Components/Hero.svelte';
    import About from '../Components/About.svelte';
    import Skills from '../Components/Skills.svelte';
    import ExperienceTimeline from '../Components/ExperienceTimeline.svelte';
    import StudyTimeline from '../Components/StudyTimeline.svelte';
    import Contact from '../Components/Contact.svelte';
    import Footer from '../Components/Footer.svelte';

    /**
     * Real props sent by `HomeController@index`: `profile` (single object),
     * `skills`/`experiences`/`studies` (collections, already ordered
     * server-side). `locale` is shared globally on every Inertia response
     * by `HandleInertiaRequests::share()` (PR9) — forwarded straight
     * through to `FrontLayout` for `LocaleSwitcher`'s active-language state.
     * `profile` is also forwarded to `FrontLayout` (PR8c) purely for the
     * nav's specialty tagline under the "Baubyte" wordmark.
     */
    let {
        profile,
        skills = [],
        experiences = [],
        studies = [],
        locale = 'es',
        yearsOfExperience = null,
    } = $props();
</script>

<FrontLayout {locale} {profile}>
    <!--
        The CV download CTA now lives inside `Hero`'s left column (PR8c),
        alongside the social icons — it used to be a standalone block here.
    -->
    <Hero {profile} {yearsOfExperience} />

    <About {profile} />
    <Skills {skills} />
    <ExperienceTimeline {experiences} />
    <StudyTimeline {studies} />
    <Contact {profile} />

    <Footer {profile} />
</FrontLayout>
