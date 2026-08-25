<script>
    import { scrollReveal } from '../lib/scrollReveal.js';
    import { typewriter } from '../lib/typewriter.js';
    import { t } from '../lib/i18n.js';
    import SkillBar from './SkillBar.svelte';

    /**
     * `skills` arrives already ordered by `name` server-side (see
     * `HomeController@index`). Grouped by `skill.category` — a REAL column
     * on the `skills` table, editable from Filament — not derived from
     * `skillIcons.js`'s map. `skillIcons.js` only resolves the per-name
     * icon now; category is entirely the owner's to organize from the
     * admin (any string, any grouping) without a code change.
     */
    let { skills = [] } = $props();

    const groups = $derived.by(() => {
        const byCategory = new Map();
        const fallbackCategory = t('skills.other');

        for (const skill of skills) {
            const category = skill.category?.trim() || fallbackCategory;

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
        <h2
            class="mb-10 text-center font-display text-3xl font-semibold text-base-content sm:text-4xl"
            use:typewriter={{ revealDelay: 0 }}
        >
            {t('skills.title')}
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
