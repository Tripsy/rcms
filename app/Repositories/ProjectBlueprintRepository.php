<?php

declare(strict_types=1);

namespace App\Repositories;

use App\Repositories\Traits\CacheRepositoryTrait;

class ProjectBlueprintRepository
{
    use CacheRepositoryTrait;

    const CACHE_MODEL = 'project_blueprint';

    const CACHE_TIME = 86400;

    public function getViewCache(int $id, callable $cacheContent)
    {
        return $this
            ->initCacheKey()
            ->addCachePiece($id)
            ->getCacheContent($cacheContent);
    }
}
