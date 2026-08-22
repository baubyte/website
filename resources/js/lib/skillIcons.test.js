import { describe, expect, test } from 'vitest';
import { getSkillMeta } from './skillIcons.js';

describe('getSkillMeta', () => {
    test('matches a known legacy-cased skill name and returns display-correct casing', () => {
        // Real DB data (legacy import) stores skill names uppercase, e.g.
        // "JAVA", "PYTHON" — the stored value is never mutated, only the
        // display label returned here changes.
        expect(getSkillMeta('JAVA')).toEqual({
            icon: expect.anything(),
            label: 'Java',
        });

        expect(getSkillMeta('PYTHON')).toEqual({
            icon: expect.anything(),
            label: 'Python',
        });
    });

    test('keeps acronym-style names uppercase in the display label', () => {
        expect(getSkillMeta('SQL').label).toBe('SQL');
        expect(getSkillMeta('HTML').label).toBe('HTML');
        expect(getSkillMeta('CSS').label).toBe('CSS');
        expect(getSkillMeta('PHP').label).toBe('PHP');
    });

    test('matches case-insensitively (future Filament entries may not be all-caps)', () => {
        expect(getSkillMeta('java')).toEqual(getSkillMeta('JAVA'));
        expect(getSkillMeta('Java')).toEqual(getSkillMeta('JAVA'));
    });

    test('falls back to a generic icon for an unmapped skill, without hiding it', () => {
        const meta = getSkillMeta('Kubernetes');

        expect(meta.icon).toBeTruthy();
        expect(meta.label).toBe('Kubernetes');
    });

    test('never throws for an empty or missing skill name', () => {
        expect(() => getSkillMeta('')).not.toThrow();
        expect(() => getSkillMeta(undefined)).not.toThrow();
    });

    test('does not resolve category — that lives on skills.category, not this map', () => {
        expect(getSkillMeta('JAVA').category).toBeUndefined();
        expect(getSkillMeta('Kubernetes').category).toBeUndefined();
    });
});
