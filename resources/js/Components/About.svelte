<script>
    import { scrollReveal } from '../lib/scrollReveal.js';

    /**
     * `profile.description`/`profile.language` arrive already resolved to
     * the active session locale (see `HomeController@index` / PR9's
     * `ResolvesLocalizedFields`) — this component never touches the raw
     * `_es`/`_en` fields.
     */
    let { profile } = $props();
</script>

<section id="about" class="container mx-auto px-4 py-20 sm:py-28" use:scrollReveal>
    <div class="mx-auto max-w-4xl">
        <h2 class="mb-8 font-display text-3xl font-semibold text-base-content sm:text-4xl">About</h2>

        <div class="grid gap-10 md:grid-cols-3">
            <p class="whitespace-pre-line text-base leading-relaxed text-base-content/80 md:col-span-2">
                {profile?.description ?? ''}
            </p>

            <dl class="space-y-4 md:border-l md:border-base-300 md:pl-8">
                {#if profile?.email_contact}
                    <div>
                        <dt class="text-xs font-semibold uppercase tracking-wide text-base-content/50">Email</dt>
                        <dd class="mt-1">{profile.email_contact}</dd>
                    </div>
                {/if}
                {#if profile?.language}
                    <div>
                        <dt class="text-xs font-semibold uppercase tracking-wide text-base-content/50">Language</dt>
                        <dd class="mt-1">{profile.language}</dd>
                    </div>
                {/if}
            </dl>
        </div>
    </div>
</section>
