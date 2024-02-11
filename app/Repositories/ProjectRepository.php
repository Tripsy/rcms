<?php

declare(strict_types=1);

namespace App\Repositories;

use App\Repositories\Traits\CacheRepositoryTrait;

class ProjectRepository
{
    use CacheRepositoryTrait;

    const CACHE_MODEL = 'project';

    const CACHE_TIME = 86400;

    public function getListCache(array $data, callable $cacheContent)
    {
        return $this
            ->buildCacheTags(['list'])
            ->buildCacheKey($data)
            ->getCacheContent($cacheContent);
    }

    public function getViewCache(int $id, callable $cacheContent)
    {
        return $this
            ->buildCacheTags()
            ->buildCacheKey($id)
            ->getCacheContent($cacheContent);
    }
}
