<?php

declare(strict_types=1);

namespace App\Actions;

use App\Actions\Traits\AsAction;
use App\Commands\ProjectStatusUpdateCommand;
use App\Exceptions\ActionException;
use App\Queries\ProjectQuery;

class ProjectStatusUpdate
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
    public function handle(ProjectStatusUpdateCommand $command): void
    {
        $this->query
            ->filterById($command->getId())
            ->updateFirst([
                'status' => $command->getStatus(),
            ]);
    }
}
