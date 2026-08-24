// Regenerates resources/icons/devicon.json, read server-side only by App\Support\Icons\IconCatalog.
import { mkdir, writeFile } from 'node:fs/promises';
import { dirname, resolve } from 'node:path';
import { fileURLToPath } from 'node:url';

const collection = await import('@iconify-json/devicon/icons.json', {
    with: { type: 'json' },
}).then((module) => module.default);

const projectRoot = resolve(dirname(fileURLToPath(import.meta.url)), '..');
const outputPath = resolve(projectRoot, 'resources/icons/devicon.json');

// Only the fields IconCatalog actually reads.
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
