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
 * Real skills currently stored in the DB (legacy-imported): "C#",
 * "C++", "CSS", "HTML", "JAVA", "PHP", "PYTHON", "SASS", "SQL" — all
 * uppercase/legacy-cased. This map is keyed by the UPPERCASED skill name so
 * matching stays case-insensitive (future entries added from Filament won't
 * necessarily be all-caps), and each entry carries its own display `label`
 * rather than deriving casing algorithmically — acronyms ("SQL", "HTML",
 * "CSS", "PHP") and real words ("Java", "Python", "Sass") don't follow one
 * consistent casing rule, so a per-entry label is more correct than a
 * blanket title-case transform.
 *
 * This map ONLY resolves icon + display label — grouping/category lives on
 * `skills.category`, a real editable column (see the `add_category_to_skills`
 * migration and `SkillResource`), not here. The owner explicitly wants to
 * reorganize categories from the admin without a code change, so this file
 * must never be the source of truth for category.
 *
 * The stored `Skill.name` value itself is NEVER mutated — this only affects
 * what is rendered.
 */
const SKILL_META = {
    'C#': { icon: iconCsharp, label: 'C#' },
    'C++': { icon: iconCplusplus, label: 'C++' },
    CSS: { icon: iconCss3, label: 'CSS' },
    HTML: { icon: iconHtml5, label: 'HTML' },
    JAVA: { icon: iconJava, label: 'Java' },
    PHP: { icon: iconPhp, label: 'PHP' },
    PYTHON: { icon: iconPython, label: 'Python' },
    SASS: { icon: iconSass, label: 'Sass' },
    // No dedicated generic "SQL" logo exists in `devicon` (only per-vendor
    // ones like `mysql`/`postgresql`) and the stored skill is the generic
    // "SQL" acronym, not a specific vendor — using a generic database icon
    // rather than guessing/inventing a specific database product.
    SQL: { icon: iconDatabase, label: 'SQL' },
};

function toTitleCase(value) {
    return value
        .split(/\s+/)
        .filter(Boolean)
        .map((word) => word[0].toUpperCase() + word.slice(1).toLowerCase())
        .join(' ');
}

/**
 * Resolves display metadata (icon + label) for a skill name. Never throws
 * and never hides a skill: an unrecognized name still renders with a
 * generic code icon, title-cased as a best-effort display label.
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
    };
}
