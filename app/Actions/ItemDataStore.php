<?php

namespace App\Actions;

use App\Actions\Traits\AsAction;
use App\Commands\ItemDataStoreCommand;
use App\Repositories\Interfaces\ItemDataRepositoryInterface;
use App\Repositories\Interfaces\ItemRepositoryInterface;

class ItemDataStore
{
    use AsAction;

    private ItemRepositoryInterface $itemRepository;

    private ItemDataRepositoryInterface $itemDataRepository;

    public function __construct(ItemRepositoryInterface $itemRepository, ItemDataRepositoryInterface $itemDataRepository)
    {
        $this->itemRepository = $itemRepository;
        $this->itemDataRepository = $itemDataRepository;
    }

    public function handle(ItemDataStoreCommand $command): void
    {
        $this->itemDataRepository->create([
            'uuid' => $command->getUuid(),
            'label' => $command->getLabel(),
            'content' => $command->getContent(),
        ]);
    }
}
