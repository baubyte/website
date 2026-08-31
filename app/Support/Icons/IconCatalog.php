<?php

namespace App\Support\Icons;

// Read-only lookup over resources/icons/devicon.json, regenerated via `npm run icons:build`. Server-side only.
class IconCatalog
{
    /**
     * @var array{prefix: string, width: int, height: int, icons: array<string, array{body: string, width?: int, height?: int}>, aliases: array<string, array{parent: string}>}|null
     */
    private static ?array $data = null;

    /**
     * @return list<array{id: string, label: string}>
     */
    public static function search(string $term, int $limit = 50): array
    {
        $data = self::data();
        $needle = mb_strtolower(trim($term));
        $results = [];

        foreach (array_keys($data['icons']) as $name) {
            if ($needle !== '' && ! str_contains(mb_strtolower($name), $needle)) {
                continue;
            }

            $results[] = [
                'id' => "{$data['prefix']}:{$name}",
                'label' => self::label($name),
            ];

            if (count($results) >= $limit) {
                break;
            }
        }

        return $results;
    }

    public static function has(?string $id): bool
    {
        return self::resolveName($id) !== null;
    }

    /**
     * @return array{body: string, width: int, height: int}|null
     */
    public static function resolve(?string $id): ?array
    {
        $data = self::data();
        $name = self::resolveName($id);

        if ($name === null) {
            return null;
        }

        $icon = $data['icons'][$name];

        return [
            'body' => $icon['body'],
            'width' => $icon['width'] ?? $data['width'],
            'height' => $icon['height'] ?? $data['height'],
        ];
    }

    public static function labelFor(?string $id): ?string
    {
        $name = self::resolveName($id);

        return $name === null ? null : self::label($name);
    }

    /**
     * @param  list<array{id: string, label: string}>  $entries
     * @return array<string, string>
     */
    public static function options(array $entries): array
    {
        $options = [];

        foreach ($entries as $entry) {
            $options[$entry['id']] = self::renderOption($entry['id'], $entry['label']);
        }

        return $options;
    }

    // SECURITY: allowHtml() / html() renders this raw — SVG must come only from IconCatalog::resolve(), never raw input.
    public static function renderOption(string $id, ?string $label = null, int $size = 20): string
    {
        $label ??= self::labelFor($id) ?? $id;
        $icon = self::resolve($id);

        if ($icon === null) {
            return e($label);
        }

        return sprintf(
            '<span class="inline-flex items-center gap-2" style="display: inline-flex; align-items: center; gap: 0.5rem; vertical-align: middle;"><svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 %d %d" width="%d" height="%d" class="shrink-0" style="width: %dpx; height: %dpx; flex-shrink: 0; display: inline-block; vertical-align: middle;">%s</svg><span>%s</span></span>',
            $icon['width'],
            $icon['height'],
            $size,
            $size,
            $size,
            $size,
            $icon['body'],
            e($label),
        );
    }

    private static function resolveName(?string $id): ?string
    {
        if ($id === null || ! str_contains($id, ':')) {
            return null;
        }

        $data = self::data();
        [$prefix, $name] = explode(':', $id, 2);

        if ($prefix !== $data['prefix']) {
            return null;
        }

        // Bounded in case a future catalog ever nests aliases.
        for ($hops = 0; $hops < 5 && $name !== null; $hops++) {
            if (isset($data['icons'][$name])) {
                return $name;
            }

            $name = $data['aliases'][$name]['parent'] ?? null;
        }

        return null;
    }

    private static function label(string $name): string
    {
        return ucwords(str_replace(['-', '_'], ' ', $name));
    }

    /**
     * @return array{prefix: string, width: int, height: int, icons: array<string, array{body: string, width?: int, height?: int}>, aliases: array<string, array{parent: string}>}
     */
    private static function data(): array
    {
        if (self::$data !== null) {
            return self::$data;
        }

        $path = resource_path('icons/devicon.json');

        if (! is_file($path)) {
            return self::$data = ['prefix' => 'devicon', 'width' => 24, 'height' => 24, 'icons' => [], 'aliases' => []];
        }

        $decoded = json_decode(file_get_contents($path), true);

        return self::$data = [
            'prefix' => $decoded['prefix'] ?? 'devicon',
            'width' => $decoded['width'] ?? 24,
            'height' => $decoded['height'] ?? 24,
            'icons' => $decoded['icons'] ?? [],
            'aliases' => $decoded['aliases'] ?? [],
        ];
    }
}
