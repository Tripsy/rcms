<?php

namespace App\Actions;

use App\Actions\Traits\AsAction;
use App\Commands\TagsStoreCommand;
use App\Queries\TagsCreateQuery;

class TagsStore
{
    use AsAction;

    private TagsCreateQuery $query;

    public function __construct(TagsCreateQuery $query)
    {
        $this->query = $query;
    }

    public function handle(TagsStoreCommand $command): void
    {
        $this->query->create([
            'project_id' => $command->getProjectId(),
            'name' => $command->getName(),
            'description' => $command->getDescription(),
            'is_category' => $command->getIsCategory(),
            'status' => $command->getStatus(),
        ]);
    }
}
