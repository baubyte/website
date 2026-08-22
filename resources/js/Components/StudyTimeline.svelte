<script>
    /**
     * Same ordered-list pattern as `ExperienceTimeline.svelte`; `studies`
     * arrives already ordered by `start_date desc` from `HomeController`,
     * with `title`/`description` already resolved to the active session
     * locale (PR9).
     */
    let { studies } = $props();
</script>

<section id="studies" class="container mx-auto px-4 py-12">
    <h2 class="mb-8 text-center text-2xl font-bold">Studies</h2>

    <ol class="space-y-8 border-l border-base-300 pl-6">
        {#each studies as study (study.id)}
            <li>
                <div class="text-sm text-base-content/60">
                    {study.start_date} &mdash; {study.end_date ?? 'Present'}
                </div>
                <h3 class="text-lg font-semibold">{study.title}</h3>
                <p class="text-base-content/70">{study.entity}</p>
                <p class="mt-1">{study.description}</p>
            </li>
        {/each}
    </ol>
</section>
