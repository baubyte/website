<script>
    import { scrollReveal } from '../lib/scrollReveal.js';
    import { formatMonthYear } from '../lib/formatDate.js';

    /**
     * `experiences` is already ordered by `start_date desc` server-side
     * (see `HomeController@index`) and each item's `specialty`/
     * `description` are already resolved to the active session locale
     * (PR9) — this component only renders the list in the order it
     * receives it.
     */
    let { experiences } = $props();
</script>

<section id="experience" class="py-20 sm:py-28" use:scrollReveal>
    <div class="container mx-auto max-w-3xl px-4">
        <h2 class="mb-12 text-center font-display text-3xl font-semibold text-base-content sm:text-4xl">
            Experience
        </h2>

        <ol class="relative space-y-10 border-l-2 border-base-300 pl-8">
            {#each experiences as experience, index (experience.id)}
                <li
                    class="relative"
                    use:scrollReveal={{ delay: index * 100, distance: 16 }}
                >
                    <span
                        class="absolute -left-[calc(2rem+5px)] top-1.5 h-3 w-3 rounded-full border-2 border-base-100 bg-primary"
                        aria-hidden="true"
                    ></span>

                    <div class="rounded-box border border-base-300 bg-base-100 p-5 shadow-sm">
                        <div class="text-xs font-medium uppercase tracking-wide text-primary">
                            {formatMonthYear(experience.start_date)} &mdash; {formatMonthYear(experience.end_date) ?? 'Presente'}
                        </div>
                        <h3 class="mt-1 font-display text-lg font-semibold text-base-content">
                            {experience.company}
                        </h3>
                        <p class="text-sm text-base-content/70">{experience.specialty}</p>
                        <p class="mt-2 text-base-content/80">{experience.description}</p>
                    </div>
                </li>
            {/each}
        </ol>
    </div>
</section>
