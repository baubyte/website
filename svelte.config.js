import { vitePreprocess } from '@sveltejs/vite-plugin-svelte';

// `vitePreprocess()` is what actually runs `sass` on `<style lang="scss">`
// blocks (CubeLoader.svelte, ported directly from the reference site) --
// installing the `sass` package alone isn't enough without this.
export default {
    preprocess: vitePreprocess(),
};
