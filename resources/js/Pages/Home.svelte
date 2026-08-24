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
     * by `HandleInertiaRequests::share()` — forwarded straight through to
     * `FrontLayout` for `LocaleSwitcher`'s active-language state, and to
     * `Contact` so `ChatWidget` sends the visitor's current locale to
     * `POST /api/chat`. `turnstileSiteKey` is the same kind of global
     * share -- forwarded to `Contact` so `ChatWidget` can render the
     * Cloudflare Turnstile widget; null until the owner configures a real
     * Turnstile site. `profile` is also forwarded to `FrontLayout` purely
     * for the nav's specialty tagline under the "Baubyte" wordmark.
     */
    let {
        profile,
        skills = [],
        experiences = [],
        studies = [],
        locale = 'es',
        turnstileSiteKey = null,
        yearsOfExperience = null,
    } = $props();
</script>

<FrontLayout {locale} {profile}>
    <!--
        The CV download CTA lives inside `Hero`'s left column, alongside
        the social icons -- not as a standalone block here.
    -->
    <Hero {profile} {skills} {yearsOfExperience} />

    <About {profile} />
    <Skills {skills} />
    <ExperienceTimeline {experiences} />
    <StudyTimeline {studies} />
    <Contact {locale} {turnstileSiteKey} />

    <Footer {profile} />
</FrontLayout>
