<?php

declare(strict_types=1);

namespace App\Repositories;

use App\Repositories\Traits\CacheRepositoryTrait;

class ItemRepository
{
    use CacheRepositoryTrait;

    const CACHE_MODEL = 'item';

    const CACHE_TIME = 86400;

    public function getViewCache(int $id, callable $cacheContent): mixed
    {
        return $this
            ->initCacheKey()
            ->addCachePiece($id)
            ->getCacheContent($cacheContent);
    }
}
