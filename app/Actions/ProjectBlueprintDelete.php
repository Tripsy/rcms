<?php

namespace App\Actions;

use App\Actions\Traits\AsAction;
use App\Commands\ProjectBlueprintDeleteCommand;
use App\Exceptions\ActionException;
use App\Queries\ProjectBlueprintDeleteQuery;

class ProjectBlueprintDelete
{
    use AsAction;

    private ProjectBlueprintDeleteQuery $query;

    public function __construct(ProjectBlueprintDeleteQuery $query)
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
