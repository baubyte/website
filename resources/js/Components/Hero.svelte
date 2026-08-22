<script>
    import Icon from '@iconify/svelte';
    import { iconChevronDown, iconGithub, iconInstagram, iconLinkedin } from '../lib/icons.js';

    let { profile } = $props();

    const fullName = $derived(
        `${profile?.name ?? ''} ${profile?.surname ?? ''}`.trim(),
    );

    const initials = $derived(
        `${profile?.name?.[0] ?? ''}${profile?.surname?.[0] ?? ''}`.toUpperCase(),
    );

    /**
     * `profile.avatar` points at `/uploads/profile/images/{avatar}`, a
     * legacy-migrated (PR3) upload path. In this dev environment that
     * directory/file doesn't actually exist yet (a pre-existing data/asset
     * gap from the legacy import, not introduced by this unit) — without a
     * fallback, a broken `<img>` renders its `alt` text visibly inside the
     * frame, which looks worse than no photo at all. `avatarFailed` lets
     * the frame degrade to a simple initials badge instead.
     */
    let avatarFailed = $state(false);

    const socials = $derived(
        [
            profile?.github_url && { url: profile.github_url, label: 'GitHub', icon: iconGithub },
            profile?.linkedin_url && { url: profile.linkedin_url, label: 'LinkedIn', icon: iconLinkedin },
            profile?.instagram_url && { url: profile.instagram_url, label: 'Instagram', icon: iconInstagram },
        ].filter(Boolean),
    );
</script>

<section class="hero relative min-h-[90vh] overflow-hidden bg-base-100">
    <!-- Soft decorative wash behind the composition — pure CSS blur, no JS. -->
    <div
        class="pointer-events-none absolute left-1/2 top-0 h-96 w-96 -translate-x-1/2 rounded-full bg-primary/15 blur-3xl"
        aria-hidden="true"
    ></div>

    <div class="hero-content relative z-10 flex-col gap-8 py-24 text-center">
        {#if profile?.avatar || fullName}
            <div class="avatar" class:placeholder={!profile?.avatar || avatarFailed}>
                <div
                    class="w-36 rounded-full bg-base-100 p-1.5 shadow-xl ring-4 ring-primary/30 ring-offset-4 ring-offset-base-100 sm:w-44"
                >
                    {#if profile?.avatar && !avatarFailed}
                        <img
                            class="rounded-full object-cover"
                            src={`/uploads/profile/images/${profile.avatar}`}
                            alt={fullName}
                            onerror={() => (avatarFailed = true)}
                        />
                    {:else}
                        <div class="flex items-center justify-center rounded-full bg-primary/15">
                            <span class="font-display text-4xl font-semibold text-primary">{initials}</span>
                        </div>
                    {/if}
                </div>
            </div>
        {/if}

        <div class="max-w-3xl">
            <h1 class="font-display text-5xl font-semibold leading-tight tracking-tight text-base-content sm:text-6xl md:text-7xl">
                {fullName}
            </h1>
            <p class="mt-4 text-lg text-base-content/70 sm:text-xl">
                {profile?.specialty ?? ''}
            </p>
        </div>

        {#if socials.length > 0}
            <div class="flex gap-3">
                {#each socials as social (social.url)}
                    <a
                        class="btn btn-circle btn-outline btn-sm border-base-300 text-base-content/70 hover:border-primary hover:bg-primary hover:text-primary-content sm:btn-md"
                        href={social.url}
                        target="_blank"
                        rel="noopener noreferrer"
                        aria-label={social.label}
                    >
                        <Icon icon={social.icon} width="20" height="20" />
                    </a>
                {/each}
            </div>
        {/if}
    </div>

    <a
        href="#about"
        class="absolute inset-x-0 bottom-6 z-10 mx-auto flex w-fit animate-bounce items-center text-base-content/40 transition-colors hover:text-primary"
        aria-label="Scroll to About section"
    >
        <Icon icon={iconChevronDown} width="28" height="28" aria-hidden="true" />
    </a>
</section>
