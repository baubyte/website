<script>
    /**
     * Renders the profile description. Only the `_es` field is rendered for
     * now — the real language selector (which would switch to `_en`) is
     * PR9 scope.
     */
    let { profile } = $props();
</script>

<section id="about" class="container mx-auto px-4 py-12">
    <h2 class="mb-4 text-2xl font-bold">About</h2>

    <div class="grid gap-8 md:grid-cols-2">
        <p class="whitespace-pre-line text-base-content/80">
            {profile?.description_es ?? ''}
        </p>

        <dl class="space-y-2">
            {#if profile?.email_contact}
                <div>
                    <dt class="text-sm font-semibold uppercase text-base-content/60">Email</dt>
                    <dd>{profile.email_contact}</dd>
                </div>
            {/if}
            {#if profile?.language_es}
                <div>
                    <dt class="text-sm font-semibold uppercase text-base-content/60">Language</dt>
                    <dd>{profile.language_es}</dd>
                </div>
            {/if}
        </dl>
    </div>
</section>
