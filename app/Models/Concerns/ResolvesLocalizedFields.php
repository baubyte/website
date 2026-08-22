<?php

namespace App\Models\Concerns;

/**
 * Shared helper for models that store parallel `{field}_es`/`{field}_en`
 * columns (`Profile`, `Experience`, `Study`). Resolves a single field to
 * the requested locale, falling back to the Spanish (`_es`) value when the
 * `_en` column is `null` — `_es` is `NOT NULL` on every one of these
 * columns, `_en` is nullable, so Spanish is always a safe fallback rather
 * than shipping an empty field to the client.
 */
trait ResolvesLocalizedFields
{
    protected function resolveLocalized(string $field, string $locale): ?string
    {
        return $this->{"{$field}_{$locale}"} ?? $this->{"{$field}_es"};
    }

    /**
     * @return array<string, mixed>
     */
    public function toLocalizedArray(string $locale): array
    {
        $data = $this->toArray();

        foreach ($this->localizedFields() as $field) {
            $data[$field] = $this->resolveLocalized($field, $locale);
            unset($data["{$field}_es"], $data["{$field}_en"]);
        }

        return $data;
    }

    /**
     * Base field names (without the `_es`/`_en` suffix) that this model
     * exposes in one resolved language via `toLocalizedArray()`.
     *
     * @return list<string>
     */
    abstract protected function localizedFields(): array;
}
