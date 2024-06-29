<?php

namespace App\Actions;

use App\Actions\Traits\AsAction;
use App\Commands\ItemContentDeactivateCommand;
use App\Commands\ItemContentStoreCommand;
use App\Queries\ItemContentQuery;

class ItemContentStore
{
    use AsAction;

    private ItemContentQuery $query;

    public function __construct(ItemContentQuery $query)
    {
        $this->query = $query;
    }

    public function handle(ItemContentStoreCommand $command): void
    {
        $deactivateCommand = new ItemContentDeactivateCommand(
            $command->getItemId(),
            $command->getBlueprintComponentId()
        );

        ItemContentDeactivate::run($deactivateCommand);

        $this->query->create([
            'item_id' => $command->getItemId(),
            'blueprint_component_id' => $command->getBlueprintComponentId(),
            'content' => $command->getContent(),
        ]);
    }
}
