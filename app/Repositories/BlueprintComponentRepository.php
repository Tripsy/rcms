<?php

declare(strict_types=1);

namespace App\Repositories;

use App\Actions\BlueprintComponentStore;
use App\Actions\BlueprintComponentUpdate;
use App\Commands\BlueprintComponentStoreCommand;
use App\Commands\BlueprintComponentUpdateCommand;
use App\Enums\BlueprintComponentType;
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

    /**
     * Validate a component structure
     * Returns an array with occurred errors
     *
     * $component structure:
     *      string name
     *      ?string description
     *      ?string info
     *      BlueprintComponentType component_type
     *      BlueprintComponentFormat component_format
     *      ?array type_options
     *      DefaultOption is_required
     */
    public function validateComponentStructure(array $component): array
    {
        $errors = [];

        if (in_array($component['component_type'], [
            BlueprintComponentType::SELECT,
            BlueprintComponentType::RADIO,
            BlueprintComponentType::CHECkBOX,
        ])) {
            //the component_format value is forced as option within the commands
            //if ($component['component_format'] != 'option') {
            //    $errors[] = 'validation.custom.components.component_format_option';
            //}

            if (empty($component['type_options'])) {
                $errors[] = 'validation.custom.components.type_options';
            }
        }

        return $errors;
    }

    /**
     * Update or insert component for a blueprint
     * Provided argument array $component should be checked prior with `validateComponentStructure()`
     */
    public function onUpDateHandleComponent(int $project_blueprint_id, array $component): void
    {
        $blueprintComponent = $this->blueprintComponentReadQuery
            ->filterByProjectBlueprintId($project_blueprint_id)
            ->filterByName($component['name'])
            ->first();

        if ($blueprintComponent) {
            $commandBlueprintComponent = new BlueprintComponentUpdateCommand(
                $blueprintComponent->id,
                $component['name'],
                $component['description'],
                $component['info'],
                $component['component_type'],
                $component['component_format'],
                $component['type_options'] ?? [],
                $component['is_required']
            );

            BlueprintComponentUpdate::run($commandBlueprintComponent);
        } else {
            $commandBlueprintComponent = new BlueprintComponentStoreCommand(
                $project_blueprint_id,
                $component['name'],
                $component['description'],
                $component['info'],
                $component['component_type'],
                $component['component_format'],
                $component['type_options'] ?? [],
                $component['is_required'],
                $component['status'] ?? '',
            );

            BlueprintComponentStore::run($commandBlueprintComponent);
        }
    }
}
