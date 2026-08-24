// Generates `resources/icons/devicon.json`, a vendored, PHP-readable copy of
// the `@iconify-json/devicon` collection (~1000 icons).
//
// This file is consumed server-side only by `App\Support\Icons\IconCatalog`
// (Filament's icon picker + `HomeController`'s `icon_data` resolution) — it
// is NEVER imported from `resources/js` and is NEVER shipped to the browser
// bundle. That's the whole point of vendoring it here instead of depending
// on `@iconify-json/devicon` at request time: no Node/npm dependency at
// runtime, no network fetch (see `resources/js/lib/icons.js`'s "no
// third-party runtime requests" convention, which this mirrors on the PHP
// side).
//
// Run via `npm run icons:build` whenever `@iconify-json/devicon` is
// upgraded. The output is committed to the repo like any other vendored
// asset.
import { mkdir, writeFile } from 'node:fs/promises';
import { dirname, resolve } from 'node:path';
import { fileURLToPath } from 'node:url';

const collection = await import('@iconify-json/devicon/icons.json', {
    with: { type: 'json' },
}).then((module) => module.default);

const projectRoot = resolve(dirname(fileURLToPath(import.meta.url)), '..');
const outputPath = resolve(projectRoot, 'resources/icons/devicon.json');

// Only the fields `IconCatalog` actually reads: the icon prefix (used to
// build `"{prefix}:{name}"` ids), the collection-wide default `width`/
// `height` (individual icons only carry `body`, per Iconify's format,
// falling back to these defaults), the `icons` map itself, and `aliases`
// (e.g. `web3` -> `web3js`) so alias ids resolve too.
const catalog = {
    prefix: collection.prefix,
    width: collection.width,
    height: collection.height,
    icons: collection.icons,
    aliases: collection.aliases ?? {},
};

await mkdir(dirname(outputPath), { recursive: true });
await writeFile(outputPath, `${JSON.stringify(catalog)}\n`, 'utf8');

console.log(
    `Wrote ${Object.keys(catalog.icons).length} icons (+ ${Object.keys(catalog.aliases).length} aliases) to ${outputPath}`,
);
