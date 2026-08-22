<script>
    let { skill } = $props();
</script>

<div class="w-full">
    <div class="mb-1 flex justify-between">
        <span class="font-medium">{skill.name}</span>
        <span class="text-sm text-base-content/70">{skill.percentage}%</span>
    </div>
    <div
        class="h-2 w-full rounded-full bg-base-300"
        role="progressbar"
        aria-label={skill.name}
        aria-valuenow={skill.percentage}
        aria-valuemin="0"
        aria-valuemax="100"
    >
        <div
            class="h-2 rounded-full bg-primary"
            style={`width: ${skill.percentage}%`}
            data-testid="skill-bar-fill"
        ></div>
    </div>
</div>
