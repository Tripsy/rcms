<?php

namespace App\Actions;

use App\Actions\Traits\AsAction;
use App\Commands\ProjectPermissionDeleteCommand;
use App\Exceptions\ActionException;
use App\Queries\ProjectPermissionQuery;

class ProjectPermissionDelete
{
    use AsAction;

    private ProjectPermissionQuery $query;

    public function __construct(ProjectPermissionQuery $query)
    {
        $this->query = $query;
    }

    /**
     * @throws ActionException
     */
    public function handle(ProjectPermissionDeleteCommand $command): void
    {
        $this->query
            ->filterById($command->getId())
            ->filterByProjectId($command->getProjectId())
            ->deleteFirst();
    }
}
