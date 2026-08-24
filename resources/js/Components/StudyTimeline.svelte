<script>
    import { scrollReveal } from '../lib/scrollReveal.js';
    import { typewriter } from '../lib/typewriter.js';
    import { formatMonthYear } from '../lib/formatDate.js';
    import { t } from '../lib/i18n.js';

    /**
     * Same ordered-list pattern as `ExperienceTimeline.svelte`; `studies`
     * arrives already ordered by `start_date desc` from `HomeController`,
     * with `title`/`description` already resolved to the active session
     * locale.
     */
    let { studies } = $props();
</script>

<section id="studies" class="bg-base-200 py-20 sm:py-28" use:scrollReveal>
    <div class="container mx-auto max-w-3xl px-4">
        <h2
            class="mb-12 text-center font-display text-3xl font-semibold text-base-content sm:text-4xl"
            use:typewriter
        >
            {t('studies.title')}
        </h2>

        <ol class="relative space-y-10 border-l-2 border-base-300 pl-8">
            {#each studies as study, index (study.id)}
                <li
                    class="relative"
                    use:scrollReveal={{ delay: index * 100, distance: 16 }}
                >
                    <span
                        class="absolute -left-[calc(2rem+5px)] top-1.5 h-3 w-3 rounded-full border-2 border-base-200 bg-secondary"
                        aria-hidden="true"
                    ></span>

                    <div class="rounded-box border border-base-content/15 bg-base-100 p-5 shadow-sm transition-all duration-300 ease-out hover:-translate-y-1 hover:border-secondary/50 hover:shadow-md">
                        <div class="text-xs font-medium uppercase tracking-wide text-secondary">
                            {formatMonthYear(study.start_date)} &mdash; {formatMonthYear(study.end_date) ?? t('studies.present')}
                        </div>
                        <h3 class="mt-1 font-display text-lg font-semibold text-base-content">
                            {study.title}
                        </h3>
                        <p class="text-sm text-base-content/70">{study.entity}</p>
                        <p class="mt-2 text-base text-base-content/80 sm:text-lg">{study.description}</p>
                    </div>
                </li>
            {/each}
        </ol>
    </div>
</section>
