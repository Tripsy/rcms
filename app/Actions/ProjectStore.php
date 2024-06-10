<?php

namespace App\Actions;

use App\Actions\Traits\AsAction;
use App\Commands\ProjectStoreCommand;
use App\Queries\ProjectQuery;

class ProjectStore
{
    use AsAction;

    private ProjectQuery $query;

    public function __construct(ProjectQuery $query)
    {
        $this->query = $query;
    }

    public function handle(ProjectStoreCommand $command): void
    {
        $this->query->create([
            'name' => $command->getName(),
            'authority_name' => $command->getAuthorityName(),
            'authority_key' => $command->getAuthorityKey(),
            'status' => $command->getStatus(),
        ]);
    }
}
