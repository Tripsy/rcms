<?php

namespace App\Actions;

use App\Actions\Traits\AsAction;
use App\Commands\ProjectPermissionStoreCommand;
use App\Queries\ProjectPermissionQuery;

class ProjectPermissionStore
{
    use AsAction;

    private ProjectPermissionQuery $query;

    public function __construct(ProjectPermissionQuery $query)
    {
        $this->query = $query;
    }

    public function handle(ProjectPermissionStoreCommand $command): void
    {
        $this->query->create([
            'project_id' => $command->getProjectId(),
            'user_id' => $command->getUserId(),
            'role' => $command->getRole(),
            'status' => $command->getStatus(),
        ]);
    }
}
