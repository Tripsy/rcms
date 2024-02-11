<?php

declare(strict_types=1);

namespace App\Repositories\Traits;

use App\Repositories\ProjectPermissionRepository;
use App\Repositories\ProjectRepository;
use Illuminate\Support\Str;

trait CacheRepositoryTrait
{
    /**
     * Flag which reports if the response was returned from cache
     *
     * return `true` for cache found & used
     * return `false` for cache not found & created
     */
    private bool $isCached = false;

    /**
     * Flag which signal the cache removal before get
     */
    private bool $refreshCache = false;

    private array $cacheTags;

    private string $cacheKey;

    /**
     * Getter for $this->cacheTags
     */
    public function getCacheTags(): array
    {
        return $this->cacheTags;
    }

    /**
     * Getter for $this->cacheKey
     */
    public function getCacheKey(): string
    {
        return $this->cacheKey;
    }

    /**
     * Flag which reports if the response was returned from cache
     *
     * return `true` for cache found & used
     * return `false` for cache not found & created
     */
    public function isCached(): bool
    {
        return $this->isCached;
    }

    /**
     * Update the flag `refreshCache` to true which as a result will remove the current cache first
     *
     * @return ProjectRepository|ProjectPermissionRepository|CacheRepositoryTrait
     */
    public function refreshCache(): self
    {
        $this->refreshCache = true;

        return $this;
    }

    /**
     * Return cache content based on cache tags & cache key
     */
    public function getCacheContent(callable $cacheContent): mixed
    {
        if (self::CACHE_TIME === 0) {
            return $cacheContent();
        }

        if ($this->refreshCache === true) {
            $this->removeCacheContent();
        } else {
            //set `isCached` flag before creating the cache
            $this->isCached = cache()->tags($this->getCacheTags())->has($this->getCacheKey());
        }

        return cache()->tags($this->getCacheTags())->remember($this->getCacheKey(), self::CACHE_TIME, $cacheContent);
    }

    /**
     * Return cache content based on cache tags & cache key
     */
    public function removeCacheContent(): void
    {
        cache()->tags($this->getCacheTags())->forget($this->getCacheKey());
    }

    /**
     * Return cache content based on cache tags & cache key
     */
    public function flushCacheByTags(): void
    {
        cache()->tags($this->getCacheTags())->flush();
    }

    /**
     * Build array with tags for caching.
     * [
     *      0 => "project"
     *      1 => "authority_name:name"
     * ]
     *
     * @return ProjectRepository|ProjectPermissionRepository|CacheRepositoryTrait
     */
    public function buildCacheTags(array $tags = []): self
    {
        $this->cacheTags = [];
        $this->cacheTags[] = self::CACHE_MODEL;

        $cacheData = [];

        $this->buildCacheData($tags, $cacheData);

        $this->cacheTags = array_merge($this->cacheTags, $cacheData);

        return $this;
    }

    /**
     * Build array with tags for caching.
     * page:1-limit:15-authorityName:play-zone.ro
     *
     * @return ProjectRepository|ProjectPermissionRepository|CacheRepositoryTrait
     */
    public function buildCacheKey(mixed $data): self
    {
        if (is_array($data)) {
            $cacheData = [];

            $this->buildCacheData($data, $cacheData);

            $this->cacheKey = implode('-', $cacheData);
        } else {
            $this->cacheKey = (string) $data;
        }

        return $this;
    }

    /**
     * Recursive function used to convert an array (can be multidimensional array) into to an array containing key:value entries as strings
     */
    private function buildCacheData(array $data, &$cacheData): void
    {
        foreach ($data as $k => $v) {
            if (is_array($v)) {
                $this->buildCacheData($v, $cacheData);
            } else {
                if ($v) {
                    $cacheData[] = Str::camel($k).':'.$v;
                }
            }
        }
    }
}
