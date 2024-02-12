<?php

declare(strict_types=1);

namespace App\Repositories;

use App\Repositories\Traits\CacheRepositoryTrait;

class ProjectRepository
{
    use CacheRepositoryTrait;

    const CACHE_MODEL = 'project';

    const CACHE_TIME = 86400;

    public function baseCacheKey(): self
    {
        $this->cachePieces = [];

        return $this;
    }

    public function getViewCache(int $id, callable $cacheContent)
    {
        return $this
            ->initCacheKey()
            ->addCachePiece($id)
            ->getCacheContent($cacheContent);
    }
}
