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
