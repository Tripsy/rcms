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
     *
     * @var bool
     */
    private bool $isCached = false;

    /**
     * Flag which signal the cache removal before get
     *
     * @var bool
     */
    private bool $refreshCache = false;

    private array $cacheTags;
    private string $cacheKey;

    /**
     * Getter for $this->cacheTags
     *
     * @return array
     */
    public function getCacheTags(): array
    {
        return $this->cacheTags;
    }

    /**
     * Getter for $this->cacheKey
     *
     * @return string
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
     *
     * @return bool
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
     *
     * @param callable $cacheContent
     * @return mixed
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
     *
     * @return void
     */
    public function removeCacheContent(): void
    {
        cache()->tags($this->getCacheTags())->forget($this->getCacheKey());
    }

    /**
     * Return cache content based on cache tags & cache key
     *
     * @return void
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
     * @param array $tags
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
     * @param mixed $data
     * @return ProjectRepository|ProjectPermissionRepository|CacheRepositoryTrait
     */
    public function buildCacheKey(mixed $data): self
    {
        if (is_array($data)) {
            $cacheData = [];

            $this->buildCacheData($data, $cacheData);

            $this->cacheKey = implode('-', $cacheData);
        } else {
            $this->cacheKey = (string)$data;
        }

        return $this;
    }

    /**
     * Recursive function used to convert an array (can be multidimensional array) into to an array containing key:value entries as strings
     *
     * @param array $data
     * @param $cacheData
     * @return void
     */
    private function buildCacheData(array $data, &$cacheData): void
    {
        foreach ($data as $k => $v) {
            if (is_array($v)) {
                $this->buildCacheData($v, $cacheData);
            } else {
                if ($v) {
                    $cacheData[] = Str::camel($k) . ':' . $v;
                }
            }
        }
    }
}
