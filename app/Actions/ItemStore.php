<?php

namespace App\Actions;

use App\Actions\Traits\AsAction;
use App\Commands\ItemStoreCommand;
use App\Queries\ItemQuery;

class ItemStore
{
    use AsAction;

    private ItemQuery $query;

    public function __construct(ItemQuery $query)
    {
        $this->query = $query;
    }

    public function handle(ItemStoreCommand $command): void
    {
        $this->query->create([
            'uuid' => $command->getUuid(),
            'project_blueprint_id' => $command->getProjectBlueprintId(),
            'description' => $command->getDescription(),
            'status' => $command->getStatus(),
        ]);
    }
}
