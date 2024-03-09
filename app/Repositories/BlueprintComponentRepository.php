<?php

declare(strict_types=1);

namespace App\Repositories;

use App\Actions\BlueprintComponentStore;
use App\Commands\BlueprintComponentStoreCommand;
use App\Commands\BlueprintComponentUpdateCommand;
use App\Queries\BlueprintComponentReadQuery;
use App\Repositories\Traits\CacheRepositoryTrait;

class BlueprintComponentRepository
{
    use CacheRepositoryTrait;

    const CACHE_MODEL = 'blueprint_component';

    const CACHE_TIME = 86400;

    private BlueprintComponentReadQuery $blueprintComponentReadQuery;

    public function __construct(BlueprintComponentReadQuery $blueprintComponentReadQuery)
    {
        $this->blueprintComponentReadQuery = $blueprintComponentReadQuery;
    }

    public function getViewCache(int $id, callable $cacheContent)
    {
        return $this
            ->initCacheKey()
            ->addCachePiece($id)
            ->getCacheContent($cacheContent);
    }

//    public function saveComponent(int $project_blueprint_id, array $component): void
//    {
//        $blueprintComponent = $this->blueprintComponentReadQuery
//            ->filterByProjectBlueprintId($project_blueprint_id)
//            ->filterByName($component['name'])
//            ->first();
//
//        ?? when updating or inserting???   we need to make sure name is unique per blueprintComponent
//        ?? also delete from db the ones which are not present anymore
//        if ($blueprintComponent) {
//            $commandBlueprintComponent = new BlueprintComponentUpdateCommand(
//                $blueprintComponent->id,
//                $component['name'],
//                $component['description'],
//                $component['info'],
//                $component['component_type'],
//                $component['component_format'],
//                $component['type_options'],
//                $component['is_required'],
//                $component['status'],
//            );
//
//            BlueprintComponentStore::run($commandBlueprintComponent);
//        } else {
//            $commandBlueprintComponent = new BlueprintComponentStoreCommand(
//                $project_blueprint_id,
//                $component['name'],
//                $component['description'],
//                $component['info'],
//                $component['component_type'],
//                $component['component_format'],
//                $component['type_options'],
//                $component['is_required'],
//                $component['status'],
//            );
//
//            BlueprintComponentStore::run($commandBlueprintComponent);
//        }
//    }
}
