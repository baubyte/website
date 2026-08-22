/**
 * Formats an ISO date/datetime string (as sent by `HomeController@index`,
 * e.g. `2021-09-11T00:00:00.000000Z`) into a short human-readable
 * "Mon YYYY" label (e.g. "Sep 2021") instead of showing the raw timestamp.
 *
 * Locale is a best-effort simplification for this unit: it defaults to
 * `es-ES` (the site's primary audience) rather than threading the active
 * session `locale` prop through every timeline component — a full i18n
 * pass for date formatting is out of scope here. Returns `null` for a
 * missing/empty value (callers use that to render their own "Present"
 * label) and returns the original string unchanged if it can't be parsed,
 * rather than throwing or silently hiding real data.
 */
export function formatMonthYear(value, locale = 'es-ES') {
    if (!value) {
        return null;
    }

    const date = new Date(value);

    if (Number.isNaN(date.getTime())) {
        return value;
    }

    return new Intl.DateTimeFormat(locale, {
        month: 'short',
        year: 'numeric',
    }).format(date);
}
