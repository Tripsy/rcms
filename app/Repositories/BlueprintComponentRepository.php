<?php

declare(strict_types=1);

namespace App\Repositories;

use App\Repositories\Traits\CacheRepositoryTrait;

class BlueprintComponentRepository
{
    use CacheRepositoryTrait;

    const CACHE_MODEL = 'blueprint_component';

    const CACHE_TIME = 86400;

    public function getViewCache(int $id, callable $cacheContent)
    {
        return $this
            ->initCacheKey()
            ->addCachePiece($id)
            ->getCacheContent($cacheContent);
    }
}
