<?php

namespace App\Actions;

use App\Actions\Traits\AsAction;
use App\Commands\ProjectBlueprintDeleteCommand;
use App\Exceptions\ActionException;
use App\Queries\ProjectBlueprintQuery;

class ProjectBlueprintDelete
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
    public function handle(ProjectBlueprintDeleteCommand $command): void
    {
        $this->query
            ->filterById($command->getId())
            ->deleteFirst();
    }
}
