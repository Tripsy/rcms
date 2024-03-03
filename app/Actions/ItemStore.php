<?php

namespace App\Actions;

use App\Actions\Traits\AsAction;
use App\Commands\BlueprintComponentStoreCommand;
use App\Repositories\Interfaces\ItemRepositoryInterface;

class ItemStore
{
    use AsAction;

    private ItemRepositoryInterface $itemRepository;

    public function __construct(ItemRepositoryInterface $itemRepository)
    {
        $this->itemRepository = $itemRepository;
    }

    public function handle(BlueprintComponentStoreCommand $command): void
    {
        $this->itemRepository->create([
            'uuid' => $command->getUuid(),
            'project_id' => $command->getProjectId(),
            'description' => $command->getDescription(),
            'status' => $command->getStatus(),
        ]);
    }
}
