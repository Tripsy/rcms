<?php

declare(strict_types=1);

namespace App\CommandHandlers;

use App\Commands\ItemStoreCommand;
use App\Repositories\ItemRepository;

class ItemStoreCommandHandler
{
    private ItemRepository $repository;

    public function __construct(ItemRepository $repository)
    {
        $this->repository = $repository;
    }

    public function handle(ItemStoreCommand $command): void
    {
        $this->repository->create([
            'uuid' => $command->getUuid(),
            'account_id' => $command->getAccountId(),
            'description' => $command->getDescription(),
            'status' => $command->getStatus(),
        ]);
    }
}
