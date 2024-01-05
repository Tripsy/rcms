<?php

namespace App\Actions;

use App\Actions\Traits\AsAction;
use App\Commands\ProjectPermissionDeleteCommand;
use App\Exceptions\ActionException;
use App\Queries\ProjectPermissionDeleteQuery;

class ProjectPermissionDelete
{
    use AsAction;

    private ProjectPermissionDeleteQuery $query;

    public function __construct(ProjectPermissionDeleteQuery $query)
    {
        $this->query = $query;
    }

    /**
     * @param ProjectPermissionDeleteCommand $command
     * @return void
     * @throws ActionException
     */
    public function handle(ProjectPermissionDeleteCommand $command): void
    {
        $this->query
            ->filterById($command->getId())
            ->deleteFirst();
    }
}
