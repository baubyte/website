<script>
    import { scrollReveal } from '../lib/scrollReveal.js';
    import { t } from '../lib/i18n.js';
    import ChatWidget from './ChatWidget.svelte';

    /**
     * The legacy `mailto:` link is gone. `ChatWidget` (backed by
     * `POST /api/chat`, `App\Http\Controllers\ChatController`) is now the
     * only way to reach out from this section, per the spec's "chat
     * widget replaces contact form" requirement — the two never coexist.
     * `locale` and `turnstileSiteKey` are the same values shared globally
     * by `HandleInertiaRequests::share()`, forwarded straight through from
     * `Home.svelte`.
     */
    let { locale = 'es', turnstileSiteKey = null } = $props();
</script>

<section id="contact" class="py-20 sm:py-28" use:scrollReveal>
    <div class="container mx-auto max-w-xl px-4 text-center">
        <h2 class="font-display text-3xl font-semibold text-base-content sm:text-4xl">{t('contact.title')}</h2>
        <p class="mt-3 text-base-content/70">{t('contact.subtitle')}</p>

        <div class="mt-8">
            <ChatWidget {locale} {turnstileSiteKey} />
        </div>
    </div>
</section>
