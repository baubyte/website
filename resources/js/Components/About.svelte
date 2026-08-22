<script>
    import Icon from '@iconify/svelte';
    import { scrollReveal } from '../lib/scrollReveal.js';
    import {
        iconAccount,
        iconBriefcase,
        iconEmailOutline,
        iconTranslate,
        iconGithub,
        iconLinkedin,
        iconInstagram,
    } from '../lib/icons.js';

    /**
     * `profile.description`/`profile.specialty`/`profile.language` arrive
     * already resolved to the active session locale (see
     * `HomeController@index` / PR9's `ResolvesLocalizedFields`) — this
     * component never touches the raw `_es`/`_en` fields.
     *
     * The reference site's sidebar (gustavomorinaga.dev/about) also shows
     * birthdate/age, location, and "random facts" — the `Profile` model has
     * NO such fields, and none are invented here (a privacy decision, not
     * this unit's to make). The "Details" sidebar below only renders real
     * `Profile` data: full name, specialty, contact email, the same social
     * links used in `Hero`, and languages (only when the profile actually
     * has content for it).
     */
    let { profile } = $props();

    const fullName = $derived(
        `${profile?.name ?? ''} ${profile?.surname ?? ''}`.trim(),
    );

    const socials = $derived(
        [
            profile?.github_url && { url: profile.github_url, label: 'GitHub', icon: iconGithub },
            profile?.linkedin_url && { url: profile.linkedin_url, label: 'LinkedIn', icon: iconLinkedin },
            profile?.instagram_url && { url: profile.instagram_url, label: 'Instagram', icon: iconInstagram },
        ].filter(Boolean),
    );
</script>

<section id="about" class="container mx-auto px-4 py-20 sm:py-28" use:scrollReveal>
    <div class="mx-auto max-w-4xl">
        <p class="mb-2 font-mono text-sm text-base-content/60">
            <span class="text-primary">const</span> about = <span class="text-secondary">"{fullName}"</span>;
        </p>
        <h2 class="mb-8 font-display text-3xl font-semibold text-base-content sm:text-4xl">About</h2>

        <div class="grid gap-10 md:grid-cols-3">
            <p class="whitespace-pre-line text-base leading-relaxed text-base-content/80 md:col-span-2">
                {profile?.description ?? ''}
            </p>

            <div class="md:border-l md:border-base-300 md:pl-8">
                <h3 class="mb-4 text-xs font-semibold uppercase tracking-wide text-base-content/50">
                    Details
                </h3>

                <dl class="space-y-4">
                    <div class="flex items-start gap-3">
                        <Icon
                            icon={iconAccount}
                            width="18"
                            height="18"
                            class="mt-0.5 shrink-0 text-primary"
                            aria-hidden="true"
                        />
                        <div>
                            <dt class="sr-only">Name</dt>
                            <dd>{fullName}</dd>
                        </div>
                    </div>

                    {#if profile?.specialty}
                        <div class="flex items-start gap-3">
                            <Icon
                                icon={iconBriefcase}
                                width="18"
                                height="18"
                                class="mt-0.5 shrink-0 text-primary"
                                aria-hidden="true"
                            />
                            <div>
                                <dt class="sr-only">Specialty</dt>
                                <dd>{profile.specialty}</dd>
                            </div>
                        </div>
                    {/if}

                    {#if profile?.email_contact}
                        <div class="flex items-start gap-3">
                            <Icon
                                icon={iconEmailOutline}
                                width="18"
                                height="18"
                                class="mt-0.5 shrink-0 text-primary"
                                aria-hidden="true"
                            />
                            <div>
                                <dt class="sr-only">Email</dt>
                                <dd>{profile.email_contact}</dd>
                            </div>
                        </div>
                    {/if}

                    {#if profile?.language}
                        <div class="flex items-start gap-3">
                            <Icon
                                icon={iconTranslate}
                                width="18"
                                height="18"
                                class="mt-0.5 shrink-0 text-primary"
                                aria-hidden="true"
                            />
                            <div>
                                <dt class="text-xs font-semibold uppercase tracking-wide text-base-content/50">
                                    Languages
                                </dt>
                                <dd class="mt-0.5">{profile.language}</dd>
                            </div>
                        </div>
                    {/if}
                </dl>

                {#if socials.length > 0}
                    <div class="mt-6 flex gap-2">
                        {#each socials as social (social.url)}
                            <a
                                class="btn btn-circle btn-outline btn-sm border-base-300 text-base-content/70 hover:border-primary hover:bg-primary hover:text-primary-content"
                                href={social.url}
                                target="_blank"
                                rel="noopener noreferrer"
                                aria-label={social.label}
                            >
                                <Icon icon={social.icon} width="16" height="16" />
                            </a>
                        {/each}
                    </div>
                {/if}
            </div>
        </div>
    </div>
</section>
