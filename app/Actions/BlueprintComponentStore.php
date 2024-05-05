<?php

namespace App\Actions;

use App\Actions\Traits\AsAction;
use App\Commands\BlueprintComponentStoreCommand;
use App\Queries\BlueprintComponentCreateQuery;

class BlueprintComponentStore
{
    use AsAction;

    private BlueprintComponentCreateQuery $query;

    public function __construct(BlueprintComponentCreateQuery $query)
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
