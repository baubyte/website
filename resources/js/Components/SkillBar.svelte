<script>
    import Icon from '@iconify/svelte';
    import { skillBarReveal } from '../lib/skillBarReveal.js';
    import { getSkillMeta } from '../lib/skillIcons.js';

    /**
     * `skill.name` is the raw value stored in the DB (legacy import, e.g.
     * "JAVA") — never mutated. `getSkillMeta()` only resolves what to
     * *display* (a real technology icon + display-correct casing); an
     * unmapped skill still renders via its fallback (generic icon,
     * title-cased label, "Otros" category — see `skillIcons.js`), it is
     * never hidden or allowed to crash the page.
     */
    let { skill } = $props();

    const meta = $derived(getSkillMeta(skill.name));
</script>

<div class="w-full">
    <div class="mb-1.5 flex items-center gap-2">
        <span
            class="flex h-8 w-8 shrink-0 items-center justify-center rounded-lg bg-base-200 text-2xl"
            aria-hidden="true"
        >
            <Icon icon={meta.icon} width="20" height="20" />
        </span>
        <span class="flex-1 text-sm font-medium">{meta.label}</span>
        <span class="text-xs text-base-content/60">{skill.percentage}%</span>
    </div>
    <div
        class="h-2.5 w-full overflow-hidden rounded-full bg-base-300"
        role="progressbar"
        aria-label={meta.label}
        aria-valuenow={skill.percentage}
        aria-valuemin="0"
        aria-valuemax="100"
    >
        <div
            class="h-2.5 rounded-full bg-gradient-to-r from-secondary to-primary"
            style={`width: ${skill.percentage}%`}
            data-testid="skill-bar-fill"
            use:skillBarReveal={{ percentage: skill.percentage }}
        ></div>
    </div>
</div>
