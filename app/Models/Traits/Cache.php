<?php
namespace App\Models\Traits;

use Config\Baubyte\WebsiteConfig;

trait Cache
{

    /**
     * Save cache
     *
     * Cuando se usa como callback (afterInsert, afterUpdate, afterDelete),
     * guarda los datos en caché.
     *
     * @param mixed $dataSave Los datos a guardar en caché
     * @param array<string> $paramsToName Parámetros para generar el nombre de la caché si no se especifica us
     * el nombre de la tabla.
     * @param int|null $cacheTTL Tiempo de vida de la caché en segundos (0 para no guardar en caché).
     * @return void
     */
    public function saveCache($dataSave, array|string $paramsToName = [], $cacheTTL = null){
        if (is_string($paramsToName)) {
            $paramsToName = [$paramsToName];
        }
        if (is_null($dataSave)) {
            return;
        }
        $config = config(WebsiteConfig::class);
        $cacheTTL = is_null($cacheTTL) ? $config->cacheTTL : $cacheTTL;
        if ($cacheTTL > 0) {
            cache()->save($this->generateCacheName($paramsToName), $dataSave, $cacheTTL);
        }
    }
    /**
     * Clean cache
     *
     * Cuando se usa como callback (afterInsert, afterUpdate, afterDelete),
     *
     * @param array $data Los datos pasados por el callback del modelo
     * @return array Los mismos datos sin modificar
     */
    public function cleanCache(array $data = []): array{
        cache()->clean();
        return $data;
    }
    /**
     * Delete cache by match
     *
     * Cuando se usa como callback (afterInsert, afterUpdate, afterDelete),
     * elimina todos los caches que coincidan con el patrón.
     *
     * @param array $data Los datos pasados por el callback del modelo
     * @return array Los mismos datos sin modificar
     */
    public function deleteCacheMatch(array $data = []): array{
        $patterns = [];
        $patterns[] = isset($this->table) ? "{$this->table}_*" : 'cache_*';
        if (isset($this->cacheMatchClean) && is_array($this->cacheMatchClean)) {
            foreach ($this->cacheMatchClean as $key) {
                $patterns[] = "{$key}_*";
            }
        }
        foreach ($patterns as $pattern) {
            cache()->deleteMatching($pattern);
        }
        return $data;
    }
    /**
     * Get cache
     *
     * @param array|string $paramsToName Parámetros para generar el nombre de la caché si no se especifica usa
     * el nombre de la tabla.
     * @return mixed
     */
    public function getCache(array|string $paramsToName = []): mixed{
        if (is_string($paramsToName)) {
            $paramsToName = [$paramsToName];
        }
        $cacheName = $this->generateCacheName($paramsToName);
        return cache($cacheName);
    }

    /**
     * Generate cache name
     *
     * @param array<string> $paramsToName
     * @return string
     */
    public function generateCacheName(array $paramsToName): string
    {
        $prefix = isset($this->table) ? $this->table : 'cache';
        $suffix = implode('_', $paramsToName) ?: 'default';
        return "{$prefix}_{$suffix}";
    }

}