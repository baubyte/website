import { describe, expect, test } from 'vitest';
import { formatMonthYear } from './formatDate.js';

describe('formatMonthYear', () => {
    test('formats an ISO datetime into a short month/year label', () => {
        expect(formatMonthYear('2021-09-11T00:00:00.000000Z')).toBe('sept 2021');
    });

    test('returns null for a missing value (caller renders its own "Present" label)', () => {
        expect(formatMonthYear(null)).toBeNull();
        expect(formatMonthYear(undefined)).toBeNull();
        expect(formatMonthYear('')).toBeNull();
    });

    test('returns the original string unchanged when it cannot be parsed as a date', () => {
        expect(formatMonthYear('not-a-date')).toBe('not-a-date');
    });
});
