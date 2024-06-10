<?php

namespace App\Actions;

use App\Actions\Traits\AsAction;
use App\Commands\ProjectDeleteCommand;
use App\Exceptions\ActionException;
use App\Queries\ProjectQuery;

class ProjectDelete
{
    use AsAction;

    private ProjectQuery $query;

    public function __construct(ProjectQuery $query)
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
