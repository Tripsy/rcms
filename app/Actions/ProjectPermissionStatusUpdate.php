<?php

declare(strict_types=1);

namespace App\Actions;

use App\Actions\Traits\AsAction;
use App\Commands\ProjectPermissionStatusUpdateCommand;
use App\Exceptions\ActionException;
use App\Queries\ProjectPermissionQuery;

class ProjectPermissionStatusUpdate
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
    public function handle(ProjectPermissionStatusUpdateCommand $command): void
    {
        $this->query
            ->filterById($command->getId())
            ->updateFirst([
                'status' => $command->getStatus(),
            ]);
    }
}
