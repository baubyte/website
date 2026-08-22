<script>
    import { skillBarReveal } from '../lib/skillBarReveal.js';

    let { skill } = $props();
</script>

<div class="w-full">
    <div class="mb-1.5 flex items-baseline justify-between">
        <span class="text-sm font-medium">{skill.name}</span>
        <span class="text-xs text-base-content/60">{skill.percentage}%</span>
    </div>
    <div
        class="h-2.5 w-full overflow-hidden rounded-full bg-base-300"
        role="progressbar"
        aria-label={skill.name}
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
