<?php

declare(strict_types=1);

namespace App\Actions;

use App\Actions\Traits\AsAction;
use App\Commands\ProjectPermissionStatusUpdateCommand;
use App\Exceptions\ActionException;
use App\Queries\ProjectPermissionUpdateQuery;

class ProjectPermissionStatusUpdate
{
    use AsAction;

    private ProjectPermissionUpdateQuery $query;

    public function __construct(ProjectPermissionUpdateQuery $query)
    {
        $this->query = $query;
    }

    /**
     * @param ProjectPermissionStatusUpdateCommand $command
     * @return void
     * @throws ActionException
     */
    public function handle(ProjectPermissionStatusUpdateCommand $command): void
    {
        $this->query
            ->filterById($command->getId())
            ->updateFirst([
                'status' => $command->getStatus(),
            ]);
    }
}
