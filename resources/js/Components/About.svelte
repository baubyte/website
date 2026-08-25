<script>
    import Icon from '@iconify/svelte';
    import { scrollReveal } from '../lib/scrollReveal.js';
    import { t } from '../lib/i18n.js';
    import {
        iconAccount,
        iconBriefcase,
        iconEmailOutline,
        iconTranslate,
        iconGithub,
        iconLinkedin
    } from '../lib/icons.js';
    import { typewriter } from '../lib/typewriter.js';

    let { profile } = $props();

    const fullName = $derived(
        `${profile?.name ?? ''} ${profile?.surname ?? ''}`.trim(),
    );

    const socials = $derived(
        [
            profile?.github_url && { url: profile.github_url, label: 'GitHub', icon: iconGithub },
            profile?.linkedin_url && { url: profile.linkedin_url, label: 'LinkedIn', icon: iconLinkedin },
        ].filter(Boolean),
    );
</script>

<section id="about" class="container mx-auto px-4 py-20 sm:py-28" use:scrollReveal>
    <div class="mx-auto max-w-4xl">
        <p class="mb-2 font-mono text-sm text-base-content/60">
            <span class="text-primary">const</span> about = <span class="text-secondary">"{fullName}"</span>;
        </p>
        <h2 class="mb-8 font-display text-3xl font-semibold text-base-content sm:text-4xl" use:typewriter>{t('about.title')}</h2>

        <div class="grid gap-10 md:grid-cols-3">
            <p class="whitespace-pre-line text-base leading-relaxed text-base-content/80 md:col-span-2">
                {profile?.description ?? ''}
            </p>

            <div class="md:border-l md:border-base-300 md:pl-8">
                <h3 class="mb-4 text-xs font-semibold uppercase tracking-wide text-base-content/50">
                    {t('about.details')}
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
                            <dt class="sr-only">{t('about.name')}</dt>
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
                                <dt class="sr-only">{t('about.specialty')}</dt>
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
                                <dt class="sr-only">{t('about.email')}</dt>
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
                                    {t('about.languages')}
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
