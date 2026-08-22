<script>
    let { profile } = $props();

    const fullName = $derived(
        `${profile?.name ?? ''} ${profile?.surname ?? ''}`.trim(),
    );
</script>

<section class="hero border-b border-base-300 bg-base-100 py-16">
    <div class="hero-content flex-col text-center">
        {#if profile?.avatar}
            <div class="avatar">
                <div class="w-32 rounded-full ring ring-primary ring-offset-2 ring-offset-base-100">
                    <img src={`/uploads/profile/images/${profile.avatar}`} alt={fullName} />
                </div>
            </div>
        {/if}

        <h1 class="mt-4 text-3xl font-bold">{fullName}</h1>
        <p class="text-lg text-base-content/70">{profile?.specialty ?? ''}</p>

        <div class="mt-4 flex gap-2">
            {#if profile?.github_url}
                <a
                    class="btn btn-outline btn-sm"
                    href={profile.github_url}
                    target="_blank"
                    rel="noopener noreferrer"
                >
                    GitHub
                </a>
            {/if}
            {#if profile?.linkedin_url}
                <a
                    class="btn btn-outline btn-sm"
                    href={profile.linkedin_url}
                    target="_blank"
                    rel="noopener noreferrer"
                >
                    LinkedIn
                </a>
            {/if}
        </div>
    </div>
</section>
