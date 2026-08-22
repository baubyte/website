<script>
    import ThemeToggle from '../Components/ThemeToggle.svelte';

    let { children } = $props();
</script>

<div class="min-h-screen bg-base-200 text-base-content">
    <header class="navbar border-b border-base-300 bg-base-100 px-4">
        <div class="flex-1">
            <span class="text-lg font-semibold">Baubyte</span>
        </div>
        <div class="flex-none">
            <ThemeToggle />
        </div>
    </header>

    <main>
        {@render children()}
    </main>
</div>
