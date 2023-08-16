<?php

namespace App\Actions;

use App\Actions\Traits\AsAction;
use App\Commands\ProjectDeleteCommand;
use App\Exceptions\ActionException;
use App\Queries\ProjectDeleteQuery;

class ProjectDelete
{
    use AsAction;

    private ProjectDeleteQuery $projectDeleteQuery;

    public function __construct(ProjectDeleteQuery $projectDeleteQuery)
    {
        $this->projectDeleteQuery = $projectDeleteQuery;
    }

    /**
     * @param ProjectDeleteCommand $command
     * @return void
     * @throws ActionException
     */
    public function handle(ProjectDeleteCommand $command): void
    {
        $this->projectDeleteQuery
            ->filterById($command->getId())
            ->delete();
    }
}
