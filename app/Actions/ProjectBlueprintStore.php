<?php

namespace App\Actions;

use App\Actions\Traits\AsAction;
use App\Commands\ProjectBlueprintStoreCommand;
use App\Queries\ProjectBlueprintQuery;

class ProjectBlueprintStore
{
    use AsAction;

    private ProjectBlueprintQuery $query;

    public function __construct(ProjectBlueprintQuery $query)
    {
        $this->query = $query;
    }

    public function handle(ProjectBlueprintStoreCommand $command): void
    {
        $this->query->create([
            'project_id' => $command->getProjectId(),
            'name' => $command->getName(),
            'description' => $command->getDescription(),
            'status' => $command->getStatus(),
        ]);
    }
}
