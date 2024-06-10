<?php

namespace App\Actions;

use App\Actions\Traits\AsAction;
use App\Commands\BlueprintComponentStoreCommand;
use App\Queries\BlueprintComponentQuery;

class BlueprintComponentStore
{
    use AsAction;

    private BlueprintComponentQuery $query;

    public function __construct(BlueprintComponentQuery $query)
    {
        $this->query = $query;
    }

    public function handle(BlueprintComponentStoreCommand $command): void
    {
        $this->query->create([
            'project_blueprint_id' => $command->getProjectBlueprintId(),
            'name' => $command->getName(),
            'description' => $command->getDescription(),
            'info' => $command->getInfo(),
            'component_type' => $command->getComponentType(),
            'component_format' => $command->getComponentFormat(),
            'type_options' => $command->getTypeOptions(),
            'is_required' => $command->getIsRequired(),
            'status' => $command->getStatus(),
        ]);
    }
}
