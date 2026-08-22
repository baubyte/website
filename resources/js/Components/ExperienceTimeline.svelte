<script>
    /**
     * `experiences` is already ordered by `start_date desc` server-side
     * (see `HomeController@index`) and each item's `specialty`/
     * `description` are already resolved to the active session locale
     * (PR9) — this component only renders the list in the order it
     * receives it.
     */
    let { experiences } = $props();
</script>

<section id="experience" class="container mx-auto px-4 py-12">
    <h2 class="mb-8 text-center text-2xl font-bold">Experience</h2>

    <ol class="space-y-8 border-l border-base-300 pl-6">
        {#each experiences as experience (experience.id)}
            <li>
                <div class="text-sm text-base-content/60">
                    {experience.start_date} &mdash; {experience.end_date ?? 'Present'}
                </div>
                <h3 class="text-lg font-semibold">{experience.company}</h3>
                <p class="text-base-content/70">{experience.specialty}</p>
                <p class="mt-1">{experience.description}</p>
            </li>
        {/each}
    </ol>
</section>
