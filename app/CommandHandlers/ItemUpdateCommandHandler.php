<?php

declare(strict_types=1);

namespace App\CommandHandlers;

use App\Commands\ItemStoreCommand;
use App\Repositories\ItemRepository;

class ItemUpdateCommandHandler
{
    private ItemRepository $repository;

    public function __construct(ItemRepository $repository)
    {
        $this->repository = $repository;
    }

    public function handle(ItemStoreCommand $command): void
    {
        $this->repository->update($item, [
            'description' => $command->getDescription()
        ]);
    }
}
