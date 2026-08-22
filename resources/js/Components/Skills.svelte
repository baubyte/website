<script>
    import { scrollReveal } from '../lib/scrollReveal.js';
    import SkillBar from './SkillBar.svelte';

    /**
     * `skills` arrives already ordered by `name` server-side (see
     * `HomeController@index`) — this component only renders the list in
     * the order it receives it.
     */
    let { skills = [] } = $props();
</script>

<section id="skills" class="bg-base-200 py-20 sm:py-28" use:scrollReveal>
    <div class="container mx-auto max-w-4xl px-4">
        <h2 class="mb-10 text-center font-display text-3xl font-semibold text-base-content sm:text-4xl">
            Skills
        </h2>
        <div class="grid gap-x-10 gap-y-6 sm:grid-cols-2">
            {#each skills as skill (skill.id)}
                <SkillBar {skill} />
            {/each}
        </div>
    </div>
</section>
