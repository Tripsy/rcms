<?php

declare(strict_types=1);

namespace App\Actions;

use App\Actions\Traits\AsAction;
use App\Commands\ProjectPermissionUpdateCommand;
use App\Exceptions\ActionException;
use App\Queries\ProjectPermissionUpdateQuery;

class ProjectPermissionUpdate
{
    use AsAction;

    private ProjectPermissionUpdateQuery $query;

    public function __construct(ProjectPermissionUpdateQuery $query)
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
            ->updateFirst([
                'role' => $command->getRole(),
            ]);
    }
}
