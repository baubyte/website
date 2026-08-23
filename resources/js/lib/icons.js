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
export { default as iconChevronDown } from '@iconify-icons/mdi/chevron-down';
export { default as iconEmailOutline } from '@iconify-icons/mdi/email-outline';
export { default as iconDownload } from '@iconify-icons/mdi/download';
// Small floating badges on the Hero's decorative avatar frame (PR8c).
export { default as iconCodeTags } from '@iconify-icons/mdi/code-tags';
export { default as iconCreation } from '@iconify-icons/mdi/creation';
export { default as iconCalendarClock } from '@iconify-icons/mdi/calendar-clock';
// About sidebar ("Details") icon+data rows (PR8d).
export { default as iconAccount } from '@iconify-icons/mdi/account';
export { default as iconBriefcase } from '@iconify-icons/mdi/briefcase-outline';
export { default as iconTranslate } from '@iconify-icons/mdi/translate';
// Generic fallback for any skill without a dedicated logo in
// `skillIcons.js` (PR8d) — a plain code-braces glyph, not a broken/missing
// icon, so an unmapped skill never renders without a visual.
export { default as iconCodeBraces } from '@iconify-icons/mdi/code-braces';
// Generic database glyph used for the "SQL" skill (no vendor-neutral logo
// exists in `devicon` — see `skillIcons.js`).
export { default as iconDatabase } from '@iconify-icons/mdi/database';
// `ChatWidget`'s send button (PR12).
export { default as iconSend } from '@iconify-icons/mdi/send';
