<?php

declare(strict_types=1);

namespace App\Repositories;

use App\Models\ItemContent;
use App\Queries\ItemContentQuery;
use App\Repositories\Traits\CacheRepositoryTrait;

class ItemContentRepository
{
    use CacheRepositoryTrait;

    const CACHE_MODEL = 'itemContent';

    const CACHE_TIME = 86400;

    public function getViewCache(int $id, callable $cacheContent): mixed
    {
        return $this
            ->initCacheKey()
            ->addCachePiece($id)
            ->getCacheContent($cacheContent);
    }

    public function getActiveContent(int $item_id, int $blueprint_component_id): ?ItemContent
    {
        return app(ItemContentQuery::class)
            ->filterByItemId($item_id)
            ->filterByBlueprintComponentId($blueprint_component_id)
            ->isActive()
            ->first();
    }
}
