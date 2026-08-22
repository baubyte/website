<script>
    /**
     * `profile.description`/`profile.language` arrive already resolved to
     * the active session locale (see `HomeController@index` / PR9's
     * `ResolvesLocalizedFields`) — this component never touches the raw
     * `_es`/`_en` fields.
     */
    let { profile } = $props();
</script>

<section id="about" class="container mx-auto px-4 py-12">
    <h2 class="mb-4 text-2xl font-bold">About</h2>

    <div class="grid gap-8 md:grid-cols-2">
        <p class="whitespace-pre-line text-base-content/80">
            {profile?.description ?? ''}
        </p>

        <dl class="space-y-2">
            {#if profile?.email_contact}
                <div>
                    <dt class="text-sm font-semibold uppercase text-base-content/60">Email</dt>
                    <dd>{profile.email_contact}</dd>
                </div>
            {/if}
            {#if profile?.language}
                <div>
                    <dt class="text-sm font-semibold uppercase text-base-content/60">Language</dt>
                    <dd>{profile.language}</dd>
                </div>
            {/if}
        </dl>
    </div>
</section>
