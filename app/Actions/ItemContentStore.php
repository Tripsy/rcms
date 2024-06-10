<?php

namespace App\Actions;

use App\Actions\Traits\AsAction;
use App\Commands\ItemContentStoreCommand;
use App\Enums\DefaultOption;
use App\Exceptions\ActionException;
use App\Queries\ItemContentQuery;
use App\Queries\ItemContentQuery;

class ItemContentStore
{
    use AsAction;

    private ItemContentQuery $createQuery;

    private ItemContentQuery $updateQuery;

    public function __construct(ItemContentQuery $createQuery, ItemContentQuery $updateQuery)
    {
        $this->createQuery = $createQuery;
        $this->updateQuery = $updateQuery;
    }

    /**
     * @throws ActionException
     */
    public function handle(ItemContentStoreCommand $command): void
    {
        $this->updateQuery
            ->filterByItemId($command->getItemId())
            ->isActive()
            ->filterBlueprintComponentId($command->getBlueprintComponentId())
            ->updateBulk([
                'is_active' => DefaultOption::YES,
            ]);

        $this->createQuery->create([
            'item_id' => $command->getItemId(),
            'blueprint_component_id' => $command->getBlueprintComponentId(),
            'content' => $command->getContent(),
        ]);
    }
}
