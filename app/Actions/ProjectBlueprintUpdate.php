<?php

declare(strict_types=1);

namespace App\Actions;

use App\Actions\Traits\AsAction;
use App\Commands\ProjectBlueprintUpdateCommand;
use App\Exceptions\ActionException;
use App\Queries\ProjectBlueprintQuery;

class ProjectBlueprintUpdate
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
    public function handle(ProjectBlueprintUpdateCommand $command): void
    {
        $this->query
            ->filterById($command->getId())
            ->filterByProjectId($command->getProjectId())
            ->updateFirst([
                'name' => $command->getName(),
                'description' => $command->getDescription(),
            ]);
    }
}
