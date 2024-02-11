<?php

namespace App\Actions;

use App\Actions\Traits\AsAction;
use App\Commands\ProjectDeleteCommand;
use App\Exceptions\ActionException;
use App\Queries\ProjectDeleteQuery;

class ProjectDelete
{
    use AsAction;

    private ProjectDeleteQuery $query;

    public function __construct(ProjectDeleteQuery $query)
    {
        $this->query = $query;
    }

    /**
     * @throws ActionException
     */
    public function handle(ProjectDeleteCommand $command): void
    {
        $this->query
            ->filterById($command->getId())
            ->deleteFirst();
    }
}
