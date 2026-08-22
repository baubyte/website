<?php

namespace App\Support\Locale;

/**
 * Session-based locale resolution, replicating the legacy CodeIgniter app's
 * approach (`app/Controllers/LocaleController.php`): no URL-prefixed
 * locales (`/es/`, `/en/`), just a `locale` session key. `es` is the
 * primary/default language — `Profile`/`Experience`/`Study` require the
 * `_es` columns (`NOT NULL`) while `_en` is optional, confirming Spanish as
 * the required baseline.
 */
class Locale
{
    public const array SUPPORTED = ['es', 'en'];

    public const string DEFAULT = 'es';

    public static function isSupported(string $locale): bool
    {
        return in_array($locale, self::SUPPORTED, true);
    }

    public static function current(): string
    {
        $locale = session('locale');

        return is_string($locale) && self::isSupported($locale) ? $locale : self::DEFAULT;
    }
}
