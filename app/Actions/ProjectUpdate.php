<?php

declare(strict_types=1);

namespace App\Actions;

use App\Actions\Traits\AsAction;
use App\Commands\ProjectUpdateCommand;
use App\Exceptions\ActionException;
use App\Queries\ProjectQuery;

class ProjectUpdate
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
    public function handle(ProjectUpdateCommand $command): void
    {
        $this->query
            ->filterById($command->getId())
            ->updateFirst([
                'name' => $command->getName(),
                'authority_name' => $command->getAuthorityName(),
                'authority_key' => $command->getAuthorityKey(),
            ]);
    }
}
