<?php

declare(strict_types=1);

namespace App\Actions;

use App\Actions\Traits\AsAction;
use App\Commands\ProjectBlueprintUpdateCommand;
use App\Queries\ProjectBlueprintUpdateQuery;

class ProjectBlueprintUpdate
{
    use AsAction;

    private ProjectBlueprintUpdateQuery $query;

    public function __construct(ProjectBlueprintUpdateQuery $query)
    {
        $this->query = $query;
    }

    public function handle(ProjectBlueprintUpdateCommand $command): void
    {
        $this->query
            ->filterById($command->getId())
            ->updateFirst([
                'name' => $command->getName(),
                'description' => $command->getDescription(),
            ]);
    }
}
