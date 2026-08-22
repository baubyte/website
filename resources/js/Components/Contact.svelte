<script>
    import Icon from '@iconify/svelte';
    import { scrollReveal } from '../lib/scrollReveal.js';
    import { iconEmailOutline } from '../lib/icons.js';

    /**
     * Real contact form/chat widget is out of scope for this unit (the
     * chat widget is a later PR) — this section only needs enough visual
     * presence to not look broken, per the task's explicit instruction.
     */
    let { profile } = $props();
</script>

<section id="contact" class="py-20 sm:py-28" use:scrollReveal>
    <div class="container mx-auto max-w-xl px-4 text-center">
        <h2 class="font-display text-3xl font-semibold text-base-content sm:text-4xl">Contact</h2>
        <p class="mt-3 text-base-content/70">Let's talk about your next project.</p>

        {#if profile?.email_contact}
            <a
                href={`mailto:${profile.email_contact}`}
                class="btn btn-primary mt-8 gap-2"
            >
                <Icon icon={iconEmailOutline} width="20" height="20" aria-hidden="true" />
                {profile.email_contact}
            </a>
        {/if}
    </div>
</section>
