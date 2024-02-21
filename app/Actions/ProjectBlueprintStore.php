<?php

namespace App\Actions;

use App\Actions\Traits\AsAction;
use App\Commands\ProjectBlueprintStoreCommand;
use App\Queries\ProjectBlueprintCreateQuery;

class ProjectBlueprintStore
{
    use AsAction;

    private ProjectBlueprintCreateQuery $query;

    public function __construct(ProjectBlueprintCreateQuery $query)
    {
        $this->query = $query;
    }

    public function handle(ProjectBlueprintStoreCommand $command): void
    {
        $this->query->create([
            'project_id' => $command->getProjectId(),
            'description' => $command->getDescription(),
            'notes' => $command->getNotes(),
            'status' => $command->getStatus(),
        ]);
    }
}
