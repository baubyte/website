<script>
    import Icon from '@iconify/svelte';
    import { iconGithub, iconLinkedin } from '../lib/icons.js';
    import { t } from '../lib/i18n.js';

    let { profile } = $props();

    const socials = $derived(
        [
            profile?.github_url && { url: profile.github_url, label: 'GitHub', icon: iconGithub },
            profile?.linkedin_url && { url: profile.linkedin_url, label: 'LinkedIn', icon: iconLinkedin }
        ].filter(Boolean),
    );

    const fullName = $derived(
        `${profile?.name ?? ''} ${profile?.surname ?? ''}`.trim(),
    );
</script>

<footer class="footer footer-center border-t border-base-300 bg-base-100 p-8 text-base-content/60">
    {#if socials.length > 0}
        <div class="flex gap-3">
            {#each socials as social (social.url)}
                <a
                    class="btn btn-circle btn-ghost btn-sm hover:text-primary"
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
    <p class="text-sm">
        &copy; {new Date().getFullYear()}{fullName ? ` ${fullName}` : ''}. {t('footer.rights_reserved')}
    </p>
</footer>
