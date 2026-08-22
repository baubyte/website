<script>
    import Icon from '@iconify/svelte';
    import FrontLayout from '../Layouts/FrontLayout.svelte';
    import Hero from '../Components/Hero.svelte';
    import About from '../Components/About.svelte';
    import Skills from '../Components/Skills.svelte';
    import ExperienceTimeline from '../Components/ExperienceTimeline.svelte';
    import StudyTimeline from '../Components/StudyTimeline.svelte';
    import Contact from '../Components/Contact.svelte';
    import Footer from '../Components/Footer.svelte';
    import { iconDownload } from '../lib/icons.js';

    /**
     * Real props sent by `HomeController@index`: `profile` (single object),
     * `skills`/`experiences`/`studies` (collections, already ordered
     * server-side). `locale` is shared globally on every Inertia response
     * by `HandleInertiaRequests::share()` (PR9) — forwarded straight
     * through to `FrontLayout` for `LocaleSwitcher`'s active-language state.
     */
    let { profile, skills = [], experiences = [], studies = [], locale = 'es' } = $props();
</script>

<FrontLayout {locale}>
    <Hero {profile} />

    <div class="container mx-auto px-4 text-center">
        <a href="/download-cv" class="btn btn-outline btn-primary btn-sm gap-2">
            <Icon icon={iconDownload} width="16" height="16" aria-hidden="true" />
            Download CV
        </a>
    </div>

    <About {profile} />
    <Skills {skills} />
    <ExperienceTimeline {experiences} />
    <StudyTimeline {studies} />
    <Contact {profile} />

    <Footer {profile} />
</FrontLayout>
