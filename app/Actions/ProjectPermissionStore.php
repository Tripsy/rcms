<?php

namespace App\Actions;

use App\Actions\Traits\AsAction;
use App\Commands\ProjectPermissionStoreCommand;
use App\Queries\ProjectPermissionCreateQuery;

class ProjectPermissionStore
{
    use AsAction;

    private ProjectPermissionCreateQuery $query;

    public function __construct(ProjectPermissionCreateQuery $query)
    {
        $this->query = $query;
    }

    public function handle(ProjectPermissionStoreCommand $command, ): void
    {
        $this->query->create([
            'project_id' => $command->getProjectId(),
            'user_id' => $command->getUserId(),
            'role' => $command->getRole(),
            'status' => $command->getStatus(),
        ]);
    }
}
