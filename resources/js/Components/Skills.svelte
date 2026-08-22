<script>
    import { scrollReveal } from '../lib/scrollReveal.js';
    import { getSkillMeta } from '../lib/skillIcons.js';
    import SkillBar from './SkillBar.svelte';

    /**
     * `skills` arrives already ordered by `name` server-side (see
     * `HomeController@index`). This component groups them by
     * `getSkillMeta().category` for display only — the category itself is
     * derived (from `skillIcons.js`'s map, "Otros" as fallback), not
     * something stored on the `Skill` model. With today's real data every
     * skill falls under "Lenguajes"; the grouping stays ready for future
     * categories (frameworks, databases, etc.) added later via Filament.
     */
    let { skills = [] } = $props();

    const groups = $derived.by(() => {
        const byCategory = new Map();

        for (const skill of skills) {
            const category = getSkillMeta(skill.name).category;

            if (!byCategory.has(category)) {
                byCategory.set(category, []);
            }

            byCategory.get(category).push(skill);
        }

        return [...byCategory.entries()];
    });
</script>

<section id="skills" class="bg-base-200 py-20 sm:py-28" use:scrollReveal>
    <div class="container mx-auto max-w-4xl px-4">
        <h2 class="mb-10 text-center font-display text-3xl font-semibold text-base-content sm:text-4xl">
            Skills
        </h2>

        <div class="space-y-10">
            {#each groups as [category, categorySkills] (category)}
                <div>
                    <h3 class="mb-4 text-xs font-semibold uppercase tracking-wide text-base-content/50">
                        {category}
                    </h3>
                    <div class="grid gap-x-10 gap-y-6 sm:grid-cols-2">
                        {#each categorySkills as skill (skill.id)}
                            <SkillBar {skill} />
                        {/each}
                    </div>
                </div>
            {/each}
        </div>
    </div>
</section>
