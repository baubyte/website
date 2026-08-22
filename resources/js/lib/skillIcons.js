import iconCsharp from '@iconify-icons/devicon/csharp';
import iconCplusplus from '@iconify-icons/devicon/cplusplus';
import iconCss3 from '@iconify-icons/devicon/css3';
import iconHtml5 from '@iconify-icons/devicon/html5';
import iconJava from '@iconify-icons/devicon/java';
import iconPhp from '@iconify-icons/devicon/php';
import iconPython from '@iconify-icons/devicon/python';
import iconSass from '@iconify-icons/devicon/sass';
import { iconCodeBraces, iconDatabase } from './icons.js';

/**
 * Real skills currently stored in the DB (legacy-imported, PR3): "C#",
 * "C++", "CSS", "HTML", "JAVA", "PHP", "PYTHON", "SASS", "SQL" — all
 * uppercase/legacy-cased. This map is keyed by the UPPERCASED skill name so
 * matching stays case-insensitive (future entries added from Filament won't
 * necessarily be all-caps), and each entry carries its own display `label`
 * rather than deriving casing algorithmically — acronyms ("SQL", "HTML",
 * "CSS", "PHP") and real words ("Java", "Python", "Sass") don't follow one
 * consistent casing rule, so a per-entry label is more correct than a
 * blanket title-case transform.
 *
 * `category` groups skills for display (`Skills.svelte`). With today's real
 * data every entry falls under "Lenguajes" — this is expected, not a
 * shortcut: the map stays extensible for when frameworks/databases/tools
 * are added later from Filament (e.g. `{ FRAMEWORKS... category: 'Frameworks' }`).
 *
 * The stored `Skill.name` value itself is NEVER mutated — this only affects
 * what is rendered.
 */
const SKILL_META = {
    'C#': { icon: iconCsharp, label: 'C#', category: 'Lenguajes' },
    'C++': { icon: iconCplusplus, label: 'C++', category: 'Lenguajes' },
    CSS: { icon: iconCss3, label: 'CSS', category: 'Lenguajes' },
    HTML: { icon: iconHtml5, label: 'HTML', category: 'Lenguajes' },
    JAVA: { icon: iconJava, label: 'Java', category: 'Lenguajes' },
    PHP: { icon: iconPhp, label: 'PHP', category: 'Lenguajes' },
    PYTHON: { icon: iconPython, label: 'Python', category: 'Lenguajes' },
    SASS: { icon: iconSass, label: 'Sass', category: 'Lenguajes' },
    // No dedicated generic "SQL" logo exists in `devicon` (only per-vendor
    // ones like `mysql`/`postgresql`) and the stored skill is the generic
    // "SQL" acronym, not a specific vendor — using a generic database icon
    // rather than guessing/inventing a specific database product.
    SQL: { icon: iconDatabase, label: 'SQL', category: 'Lenguajes' },
};

const FALLBACK_CATEGORY = 'Otros';

function toTitleCase(value) {
    return value
        .split(/\s+/)
        .filter(Boolean)
        .map((word) => word[0].toUpperCase() + word.slice(1).toLowerCase())
        .join(' ');
}

/**
 * Resolves display metadata (icon + label + category) for a skill name.
 * Never throws and never hides a skill: an unrecognized name still renders
 * with a generic code icon under the "Otros" category, title-cased as a
 * best-effort display label.
 */
export function getSkillMeta(name) {
    const trimmed = (name ?? '').trim();
    const known = SKILL_META[trimmed.toUpperCase()];

    if (known) {
        return known;
    }

    return {
        icon: iconCodeBraces,
        label: toTitleCase(trimmed),
        category: FALLBACK_CATEGORY,
    };
}
