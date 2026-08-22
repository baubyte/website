<script>
    import 'atropos/css';
    import Icon from '@iconify/svelte';
    import { tilt } from '../lib/tilt.js';
    import {
        iconCodeTags,
        iconCreation,
        iconChevronDown,
        iconDownload,
        iconGithub,
        iconInstagram,
        iconLinkedin,
    } from '../lib/icons.js';

    let { profile } = $props();

    const fullName = $derived(
        `${profile?.name ?? ''} ${profile?.surname ?? ''}`.trim(),
    );

    const initials = $derived(
        `${profile?.name?.[0] ?? ''}${profile?.surname?.[0] ?? ''}`.toUpperCase(),
    );

    /**
     * `profile.avatar` stores a path relative to the `public` storage disk
     * (e.g. `profiles/xxxx.png`), served through the `storage:link` symlink
     * at `/storage/...` — NOT the legacy CI4 `/uploads/profile/images/...`
     * path, which was a real bug (fixed after the owner uploaded a real
     * photo and it still 404'd). `avatarFailed` still guards against a
     * broken/missing file: a broken `<img>` renders its `alt` text visibly
     * inside the frame, which looks worse than no photo at all, so it
     * degrades to a simple initials badge instead.
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

<section class="relative overflow-hidden bg-base-100 py-20 sm:py-28">
    <!-- Soft decorative wash behind the composition — pure CSS blur, no JS. -->
    <div
        class="pointer-events-none absolute right-0 top-0 h-96 w-96 translate-x-1/3 -translate-y-1/3 rounded-full bg-primary/15 blur-3xl"
        aria-hidden="true"
    ></div>

    <div class="container relative z-10 mx-auto grid grid-cols-1 items-center gap-14 px-4 md:grid-cols-2 md:gap-16">
        <!-- Left column: eyebrow, glowing title, tagline, CTA row. -->
        <div class="text-center md:text-left">
            <p class="mb-4 font-mono text-sm text-base-content/60">
                <span class="text-primary">const</span> developer = <span class="text-secondary">"{fullName}"</span>;
            </p>

            <h1 class="text-glow font-display text-5xl font-semibold leading-tight tracking-tight text-base-content sm:text-6xl md:text-7xl">
                {fullName}
            </h1>

            <p class="mx-auto mt-4 max-w-md text-lg text-base-content/70 sm:text-xl md:mx-0">
                {profile?.specialty ?? ''}
            </p>

            <div class="mt-8 flex flex-col items-center gap-4 sm:flex-row md:justify-start">
                <a href="/download-cv" class="btn btn-primary gap-2">
                    <Icon icon={iconDownload} width="18" height="18" aria-hidden="true" />
                    Download CV
                </a>

                {#if socials.length > 0}
                    <div class="flex gap-2">
                        {#each socials as social (social.url)}
                            <a
                                class="btn btn-circle btn-outline btn-sm border-base-300 text-base-content/70 hover:border-primary hover:bg-primary hover:text-primary-content"
                                href={social.url}
                                target="_blank"
                                rel="noopener noreferrer"
                                aria-label={social.label}
                            >
                                <Icon icon={social.icon} width="18" height="18" />
                            </a>
                        {/each}
                    </div>
                {/if}
            </div>
        </div>

        <!--
            Right column: avatar inside a decorative geometric frame +
            floating badges, tilted via Atropos on mouse move — the same
            library gustavomorinaga.dev uses for this exact composition
            (confirmed by reading its real source: `atropos/svelte` wrapping
            a triangle + cropped photo + floating "popup" badges). Atropos
            requires this exact nested class structure (it queries for these
            classes rather than creating them) — see `lib/tilt.js`.
        -->
        {#if profile?.avatar || fullName}
            <div
                class="atropos relative mx-auto h-[280px] w-[280px] sm:h-[340px] sm:w-[340px]"
                use:tilt
            >
                <div class="atropos-scale h-full w-full">
                    <div class="atropos-rotate h-full w-full">
                        <div class="atropos-inner relative flex h-full w-full items-center justify-center">
                            <svg
                                class="pointer-events-none absolute inset-0 h-full w-full text-primary"
                                viewBox="0 0 200 200"
                                fill="none"
                                aria-hidden="true"
                                data-atropos-offset="0"
                            >
                                <polygon points="100,4 196,178 4,178" stroke="currentColor" stroke-width="1.25" opacity="0.5" />
                            </svg>

                            <!--
                                The triangle's visual centroid (points
                                100,4 / 196,178 / 4,178 in a 200x200 box)
                                sits at y=120 -- 10% lower than the
                                container's geometric center (y=100) that
                                flex `items-center` aligns to by default.
                                Left uncorrected, the avatar reads as
                                floating near the apex instead of resting
                                inside the triangle's visual mass.
                            -->
                            <div
                                class="avatar translate-y-[10%]"
                                class:placeholder={!profile?.avatar || avatarFailed}
                                data-atropos-offset="2"
                            >
                                <div
                                    class="w-36 rounded-full bg-base-100 p-1.5 shadow-xl ring-4 ring-primary/30 ring-offset-4 ring-offset-base-100 sm:w-44"
                                >
                                    {#if profile?.avatar && !avatarFailed}
                                        <img
                                            class="rounded-full object-cover"
                                            src={`/storage/${profile.avatar}`}
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
                        </div>
                    </div>
                </div>

                <!--
                    Atropos' own `.atropos-inner` sets `overflow: hidden`
                    (it clips the tilting card) — badges meant to float
                    OUTSIDE that card's edge get clipped if placed inside
                    it. Atropos looks for `[data-atropos-offset]` anywhere
                    under the `.atropos` root (confirmed in its source:
                    `childrenRootEl = el`, the root itself, not
                    `.atropos-inner`), so these still get the same
                    parallax depth as siblings of `.atropos-scale`.
                -->
                <div
                    class="pointer-events-none absolute -left-4 -top-2 flex h-11 w-11 items-center justify-center rounded-lg border border-primary/40 bg-base-100 text-primary shadow-lg sm:-left-8 sm:-top-4"
                    aria-hidden="true"
                    data-atropos-offset="5"
                >
                    <Icon icon={iconCodeTags} width="20" height="20" />
                </div>
                <div
                    class="pointer-events-none absolute -right-4 bottom-8 flex h-11 w-11 items-center justify-center rounded-lg border border-primary/40 bg-base-100 text-primary shadow-lg sm:-right-8"
                    aria-hidden="true"
                    data-atropos-offset="8"
                >
                    <Icon icon={iconCreation} width="20" height="20" />
                </div>
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
