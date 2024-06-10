<?php

declare(strict_types=1);

namespace App\Actions;

use App\Actions\Traits\AsAction;
use App\Commands\ProjectBlueprintStatusUpdateCommand;
use App\Exceptions\ActionException;
use App\Queries\ProjectBlueprintQuery;

class ProjectBlueprintStatusUpdate
{
    use AsAction;

    private ProjectBlueprintQuery $query;

    public function __construct(ProjectBlueprintQuery $query)
    {
        $this->query = $query;
    }

    /**
     * @throws ActionException
     */
    public function handle(ProjectBlueprintStatusUpdateCommand $command): void
    {
        $this->query
            ->filterById($command->getId())
            ->updateFirst([
                'status' => $command->getStatus(),
            ]);
    }
}
