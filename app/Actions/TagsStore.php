<?php

namespace App\Actions;

use App\Actions\Traits\AsAction;
use App\Commands\TagsStoreCommand;
use App\Queries\TagQuery;

class TagsStore
{
    use AsAction;

    private TagQuery $query;

    public function __construct(TagQuery $query)
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
