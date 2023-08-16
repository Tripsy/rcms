<?php

declare(strict_types=1);

namespace App\Actions;

use App\Actions\Traits\AsAction;
use App\Commands\ProjectUpdateCommand;
use App\Exceptions\ActionException;
use App\Queries\ProjectUpdateQuery;

class ProjectUpdate
{
    use AsAction;

    private ProjectUpdateQuery $projectUpdateQuery;

    public function __construct(ProjectUpdateQuery $projectUpdateQuery)
    {
        $this->projectUpdateQuery = $projectUpdateQuery;
    }

    /**
     * @param ProjectUpdateCommand $command
     * @return void
     * @throws ActionException
     */
    public function handle(ProjectUpdateCommand $command): void
    {
        $this->projectUpdateQuery
            ->filterById($command->getId())
            ->update([
                'name' => $command->getName(),
                'authority_name' => $command->getAuthorityName(),
                'authority_key' => $command->getAuthorityKey(),
            ]);
    }
}
