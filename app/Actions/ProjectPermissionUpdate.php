<?php

declare(strict_types=1);

namespace App\Actions;

use App\Actions\Traits\AsAction;
use App\Commands\ProjectPermissionUpdateCommand;
use App\Exceptions\ActionException;
use App\Queries\ProjectPermissionQuery;

class ProjectPermissionUpdate
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
    public function handle(ProjectPermissionUpdateCommand $command): void
    {
        $this->query
            ->filterById($command->getId())
            ->filterByProjectId($command->getProjectId())
            ->updateFirst([
                'role' => $command->getRole(),
            ]);
    }
}
