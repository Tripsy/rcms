<?php

declare(strict_types=1);

namespace App\Repositories;

use App\Actions\BlueprintComponentDelete;
use App\Actions\BlueprintComponentStore;
use App\Actions\BlueprintComponentUpdate;
use App\Commands\BlueprintComponentDeleteCommand;
use App\Commands\BlueprintComponentStoreCommand;
use App\Commands\BlueprintComponentUpdateCommand;
use App\Enums\BlueprintComponentType;
use App\Queries\BlueprintComponentQuery;
use App\Repositories\Traits\CacheRepositoryTrait;

class BlueprintComponentRepository
{
    use CacheRepositoryTrait;

    const CACHE_MODEL = 'blueprint_component';

    const CACHE_TIME = 86400;

    public function getViewCache(int $id, callable $cacheContent): mixed
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
     * Handle project blueprint components:
     *      - remove from DB components which are not present in $validatedComponents
     *      - save component (eg: insert or update)
     *
     * Provided argument array $validatedComponents should be checked prior with `validateComponentStructure()`
     */
    public function onUpDateHandleComponents(int $project_blueprint_id, array $validatedComponents): void
    {
        $componentsName = array_column($validatedComponents, 'name');

        $queryBlueprintComponent = app(BlueprintComponentQuery::class)
            ->filterByProjectBlueprintId($project_blueprint_id)
            ->asQuery();

        $queryBlueprintComponent
            ->whereNotIn('name', $componentsName);

        //get a list with components found in the database and not present in the $validatedComponents
        $extraComponents = $queryBlueprintComponent->get();

        foreach ($extraComponents as $extraComponent) {
            $this->deleteComponent($extraComponent->id, $extraComponent->project_blueprint_id);
        }

        foreach ($validatedComponents as $validatedComponent) {
            $this->saveComponent(
                $project_blueprint_id,
                $validatedComponent
            );
        }
    }

    /**
     * Delete blueprint component from DB
     * Note: Is not for use outside repository - there are no permissions checks
     */
    private function deleteComponent(int $id, int $project_blueprint_id): void
    {
        $command = new BlueprintComponentDeleteCommand(
            $id,
            $project_blueprint_id
        );

        BlueprintComponentDelete::run($command);
    }

    /**
     * Insert or update existing component
     * Note: Is not for use outside repository - there are no permissions checks
     */
    private function saveComponent(int $project_blueprint_id, array $component): void
    {
        $blueprintComponent = app(BlueprintComponentQuery::class)
            ->filterByProjectBlueprintId($project_blueprint_id)
            ->filterByName($component['name'])
            ->first();

        if ($blueprintComponent) {
            $commandBlueprintComponent = new BlueprintComponentUpdateCommand(
                $blueprintComponent->id,
                $blueprintComponent->project_blueprint_id,
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
