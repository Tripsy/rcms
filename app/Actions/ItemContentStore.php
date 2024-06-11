<?php

namespace App\Actions;

use App\Actions\Traits\AsAction;
use App\Commands\ItemContentStoreCommand;
use App\Enums\DefaultOption;
use App\Exceptions\ActionException;
use App\Queries\ItemContentQuery;

class ItemContentStore
{
    use AsAction;

    private ItemContentQuery $query;

    public function __construct(ItemContentQuery $query)
    {
        $this->query = $query;
    }

    /**
     * @throws ActionException
     */
    public function handle(ItemContentStoreCommand $command): void
    {
        $this->query
            ->filterByItemId($command->getItemId())
            ->isActive()
            ->filterBlueprintComponentId($command->getBlueprintComponentId())
            ->updateFirst([
                'is_active' => DefaultOption::NO,
            ]);

        $this->query->create([
            'item_id' => $command->getItemId(),
            'blueprint_component_id' => $command->getBlueprintComponentId(),
            'content' => $command->getContent(),
        ]);
    }
}
