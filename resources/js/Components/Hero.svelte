<script>
    import 'atropos/css';
    import Icon from '@iconify/svelte';
    import { route } from '../lib/route.js';
    import { t } from '../lib/i18n.js';
    import { tilt } from '../lib/tilt.js';
    import { typewriter } from '../lib/typewriter.js';
    import {
        iconCodeTags,
        iconCreation,
        iconCalendarClock,
        iconChevronDown,
        iconDownload,
        iconGithub,
        iconLinkedin,
    } from '../lib/icons.js';

    let { profile, skills = [], yearsOfExperience = null } = $props();

    /**
     * Badge labels are the real, highest-`percentage` skill within each
     * category (as set from Filament, see `skills.category`) — not
     * hardcoded strings. A category with no skills yet simply hides its
     * badge (`{#if}` below) rather than showing empty/fake content.
     */
    const topSkillIn = (category) =>
        skills
            .filter((skill) => skill.category === category)
            .slice()
            .sort((a, b) => (b.percentage ?? 0) - (a.percentage ?? 0))[0] ?? null;

    const topBackendSkill = $derived(topSkillIn('Backend'));
    const topIaSkill = $derived(topSkillIn('IA'));

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
        ].filter(Boolean),
    );
</script>

<section class="relative overflow-hidden bg-base-100 py-20 sm:py-28">
    <div
        class="pointer-events-none absolute right-0 top-0 h-96 w-96 translate-x-1/3 -translate-y-1/3 rounded-full bg-primary/15 blur-3xl"
        aria-hidden="true"
    ></div>

    <div class="container relative z-10 mx-auto grid grid-cols-1 items-center gap-14 px-4 md:grid-cols-2 md:gap-16">
        <div class="text-center md:text-left">
            <!-- Decorative only, never a real profile.fullName() call -->
            <p class="mb-4 font-mono text-sm text-base-content/60" aria-hidden="true">
                <span class="text-primary">const</span> <span class="text-base-content/80">developer</span> = <span class="text-secondary">profile</span><span class="text-base-content/60">.</span><span class="text-accent">fullName()</span><span class="text-base-content/60">;</span>
            </p>

            <h1
                class="text-glow font-display text-5xl font-semibold leading-tight tracking-tight text-base-content sm:text-6xl md:text-7xl"
                use:typewriter
            >
                {fullName}
            </h1>

            <p class="mx-auto mt-4 max-w-md text-lg text-base-content/70 sm:text-xl md:mx-0">
                {profile?.specialty ?? ''}
            </p>

            <div class="mt-8 flex flex-col items-center gap-4 sm:flex-row md:justify-start">
                <a href={route('cv.download')} class="btn btn-primary gap-2">
                    <Icon icon={iconDownload} width="18" height="18" aria-hidden="true" />
                    {t('hero.download_cv')}
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
                class="atropos group relative mx-auto h-[280px] w-[280px] sm:h-[340px] sm:w-[340px]"
                use:tilt
            >
                <div class="atropos-scale h-full w-full">
                    <div class="atropos-rotate h-full w-full">
                        <!--
                            Atropos' own stylesheet (`atropos/css`) declares
                            `.atropos-inner { display: block; }`. That rule
                            and Tailwind's `.flex` utility have equal
                            specificity, so whichever loads last in the
                            final CSS wins the cascade -- in this build
                            Atropos wins, silently turning off flex centering
                            (the avatar rendered flush at the container's
                            top-left corner, not centered, in both axes).
                            Centering now lives on a plain nested div that
                            shares no class name with Atropos, so this
                            collision can't touch it again.
                        -->
                        <div class="atropos-inner">
                            <div class="relative flex h-full w-full items-center justify-center">
                                <div
                                    class="pointer-events-none absolute inset-0 flex items-center justify-center text-primary"
                                    data-atropos-offset="0"
                                    aria-hidden="true"
                                >
                                    <!-- Halo suave interior -->
                                    <div
                                        class="absolute h-56 w-56 rounded-full bg-primary/10 blur-xl"
                                    ></div>
                                    <!-- Esquinas de Escáner / Terminal Brackets `[ ]` -->
                                    <svg
                                        class="absolute inset-2 h-[calc(100%-1rem)] w-[calc(100%-1rem)] opacity-60"
                                        viewBox="0 0 100 100"
                                        fill="none"
                                    >
                                        <!-- Esquina Superior Izquierda -->
                                        <path d="M 4 20 L 4 4 L 20 4" stroke="currentColor" stroke-width="1.75" stroke-linecap="round" />
                                        <!-- Esquina Superior Derecha -->
                                        <path d="M 80 4 L 96 4 L 96 20" stroke="currentColor" stroke-width="1.75" stroke-linecap="round" />
                                        <!-- Esquina Inferior Derecha -->
                                        <path d="M 96 80 L 96 96 L 80 96" stroke="currentColor" stroke-width="1.75" stroke-linecap="round" />
                                        <!-- Esquina Inferior Izquierda -->
                                        <path d="M 20 96 L 4 96 L 4 80" stroke="currentColor" stroke-width="1.75" stroke-linecap="round" />

                                        <!-- Guías cruzadas centrales de precisión -->
                                        <line x1="50" y1="2" x2="50" y2="8" stroke="currentColor" stroke-width="1" opacity="0.4" />
                                        <line x1="50" y1="92" x2="50" y2="98" stroke="currentColor" stroke-width="1" opacity="0.4" />
                                        <line x1="2" y1="50" x2="8" y2="50" stroke="currentColor" stroke-width="1" opacity="0.4" />
                                        <line x1="92" y1="50" x2="98" y2="50" stroke="currentColor" stroke-width="1" opacity="0.4" />
                                    </svg>
                                </div>

                                <div
                                    class="avatar"
                                    class:placeholder={!profile?.avatar || avatarFailed}
                                    data-atropos-offset="2"
                                >
                                    <div
                                        class="relative w-36 rounded-full bg-base-100 p-1.5 shadow-xl ring-4 ring-primary/30 ring-offset-4 ring-offset-base-100 sm:w-44"
                                    >
                                        {#if profile?.avatar && !avatarFailed}
                                            <!--
                                                The real photo's light office
                                                background read as a jarring
                                                bright halo against this
                                                section's dark sage palette.
                                                A slight desaturate/darken on
                                                the photo itself plus a
                                                radial vignette (using
                                                daisyUI's own `--b2` token so
                                                it tracks the active theme,
                                                same pattern as `.text-glow`
                                                in app.css) blend its edges
                                                into the frame instead of
                                                cropping/hiding real content.
                                            -->
                                            <img
                                                class="rounded-full object-cover [filter:saturate(0.85)_contrast(1.05)_brightness(0.92)]"
                                                src={`/storage/${profile.avatar}`}
                                                alt={fullName}
                                                onerror={() => (avatarFailed = true)}
                                            />
                                            <div
                                                class="pointer-events-none absolute inset-1.5 rounded-full [background:radial-gradient(circle,transparent_55%,oklch(var(--b2)/0.6)_100%)]"
                                                aria-hidden="true"
                                            ></div>
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

                    Hover-reveal label, same interaction pattern as the
                    reference's `.popup` badges (icon-only chip that
                    expands to show a text label on hover) — the label span
                    is `max-w-0 opacity-0` by default and grows on
                    `:hover`/`:focus-within` so keyboard focus reveals it
                    too, not just a mouse hover. `group` lives on the outer
                    `.atropos` frame (not on each badge) so moving the mouse
                    anywhere over the triangle/photo reveals every badge at
                    once, not just when the cursor lands on a specific
                    badge's small icon box.
                -->
                {#if topBackendSkill}
                    <button
                        type="button"
                        class="absolute -left-4 top-5 flex h-11 cursor-default items-center gap-2 overflow-hidden rounded-lg border border-primary/40 bg-base-100 px-2.5 text-primary shadow-lg transition-[width] duration-300 ease-out sm:-left-8 sm:top-7"
                        data-atropos-offset="4"
                        aria-label={topBackendSkill.name}
                    >
                        <Icon icon={iconCodeTags} width="20" height="20" class="shrink-0" />
                        <span
                            class="max-w-0 overflow-hidden text-ellipsis whitespace-nowrap text-xs font-medium opacity-0 transition-all duration-300 ease-out group-hover:max-w-[11rem] group-hover:opacity-100 group-focus-within:max-w-[11rem] group-focus-within:opacity-100"
                        >
                            {topBackendSkill.name}
                        </span>
                    </button>
                {/if}
                {#if topIaSkill}
                    <button
                        type="button"
                        class="absolute -right-4 -top-3 flex h-11 cursor-default items-center gap-2 overflow-hidden rounded-lg border border-primary/40 bg-base-100 px-2.5 text-primary shadow-lg transition-[width] duration-300 ease-out sm:-right-8 sm:-top-5"
                        data-atropos-offset="6"
                        aria-label={topIaSkill.name}
                    >
                        <Icon icon={iconCreation} width="20" height="20" class="shrink-0" />
                        <span
                            class="max-w-0 overflow-hidden text-ellipsis whitespace-nowrap text-xs font-medium opacity-0 transition-all duration-300 ease-out group-hover:max-w-[11rem] group-hover:opacity-100 group-focus-within:max-w-[11rem] group-focus-within:opacity-100"
                        >
                            {topIaSkill.name}
                        </span>
                    </button>
                {/if}
                {#if yearsOfExperience}
                    <button
                        type="button"
                        class="absolute -right-4 bottom-8 flex h-11 cursor-default items-center gap-2 overflow-hidden rounded-lg border border-primary/40 bg-base-100 px-2.5 text-primary shadow-lg transition-[width] duration-300 ease-out sm:-right-8"
                        data-atropos-offset="9"
                        aria-label={t('hero.years_experience', { count: yearsOfExperience })}
                    >
                        <Icon icon={iconCalendarClock} width="20" height="20" class="shrink-0" />
                        <span
                            class="max-w-0 overflow-hidden text-ellipsis whitespace-nowrap text-xs font-medium opacity-0 transition-all duration-300 ease-out group-hover:max-w-[11rem] group-hover:opacity-100 group-focus-within:max-w-[11rem] group-focus-within:opacity-100"
                        >
                            {t('hero.years_experience', { count: yearsOfExperience })}
                        </span>
                    </button>
                {/if}
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
