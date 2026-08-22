// Icon data imported directly (offline) from `@iconify-icons/mdi`, NOT the
// icon-name-string API (`<Icon icon="mdi:github" />`), which would make
// `@iconify/svelte`'s `Icon` component fetch SVG data from the public
// Iconify API at runtime — a real network dependency this app doesn't want
// (same "no third-party runtime requests" preference as the fonts
// decision) and one that would make component tests network-dependent.
// Passing the imported icon data object directly to `Icon`'s `icon` prop
// renders fully offline, deterministically, in tests and in production.
export { default as iconGithub } from '@iconify-icons/mdi/github';
export { default as iconLinkedin } from '@iconify-icons/mdi/linkedin';
export { default as iconInstagram } from '@iconify-icons/mdi/instagram';
export { default as iconChevronDown } from '@iconify-icons/mdi/chevron-down';
export { default as iconEmailOutline } from '@iconify-icons/mdi/email-outline';
export { default as iconDownload } from '@iconify-icons/mdi/download';
