<?php

namespace App\Actions;

use App\Actions\Traits\AsAction;
use App\Commands\ProjectStoreCommand;
use App\Queries\ProjectCreateQuery;

class ProjectStore
{
    use AsAction;

    private ProjectCreateQuery $projectCreateQuery;

    public function __construct(ProjectCreateQuery $projectCreateQuery)
    {
        $this->projectCreateQuery = $projectCreateQuery;
    }

    public function handle(ProjectStoreCommand $command, ): void
    {
        $this->projectCreateQuery->create([
            'name' => $command->getName(),
            'authority_name' => $command->getAuthorityName(),
            'authority_key' => $command->getAuthorityKey(),
            'status' => $command->getStatus(),
        ]);
    }
}
