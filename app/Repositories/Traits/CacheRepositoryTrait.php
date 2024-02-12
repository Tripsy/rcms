<?php

declare(strict_types=1);

namespace App\Repositories\Traits;

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

    /**
     * Array containing pieces from which the cache key is build
     */
    private array $cachePieces;

    /**
     * Getter for $this->cacheKey
     */
    public function getCacheKey(): string
    {
        return implode('-', $this->cachePieces);
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
     */
    public function refreshCache(): self
    {
        $this->refreshCache = true;

        return $this;
    }

    /**
     * Return cache content based on cache key
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
            $this->isCached = cache()->has($this->getCacheKey());
        }

        return cache()->remember($this->getCacheKey(), self::CACHE_TIME, $cacheContent);
    }

    /**
     * Return cache content based on cache key
     */
    public function removeCacheContent(): void
    {
        cache()->forget($this->getCacheKey());
    }

    /**
     * Append to cachePieces
     */
    public function addCachePiece(mixed $piece): self
    {
        if (is_array($piece)) {
            $this->cachePieces = array_merge($this->cachePieces, $piece);
        } else {
            $this->cachePieces[] = $piece;
        }

        return $this;
    }

    /**
     * Reset `cachePieces` and append CACHE_MODEL
     */
    public function initCacheKey(): self
    {
        $this->cachePieces = [];
        $this->cachePieces[] = self::CACHE_MODEL;

        return $this;
    }
}
