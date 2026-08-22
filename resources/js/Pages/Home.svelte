<script>
    import FrontLayout from '../Layouts/FrontLayout.svelte';
    import Hero from '../Components/Hero.svelte';
    import About from '../Components/About.svelte';
    import SkillBar from '../Components/SkillBar.svelte';
    import ExperienceTimeline from '../Components/ExperienceTimeline.svelte';
    import StudyTimeline from '../Components/StudyTimeline.svelte';

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
        <a href="/download-cv" class="btn btn-primary btn-sm">Download CV</a>
    </div>

    <About {profile} />

    <section id="skills" class="container mx-auto px-4 py-12">
        <h2 class="mb-8 text-center text-2xl font-bold">Skills</h2>
        <div class="grid gap-4 sm:grid-cols-2">
            {#each skills as skill (skill.id)}
                <SkillBar {skill} />
            {/each}
        </div>
    </section>

    <ExperienceTimeline {experiences} />
    <StudyTimeline {studies} />

    <!-- Contact: a real form/widget is out of scope for this unit (chat
    widget is PR12). This placeholder only keeps the page from looking
    broken, per the task's explicit instruction. -->
    <section id="contact" class="container mx-auto px-4 py-12 text-center">
        <h2 class="mb-4 text-2xl font-bold">Contact</h2>
        {#if profile?.email_contact}
            <p class="text-base-content/70">{profile.email_contact}</p>
        {/if}
    </section>
</FrontLayout>
