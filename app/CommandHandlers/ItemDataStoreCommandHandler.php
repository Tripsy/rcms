<?php

declare(strict_types=1);

namespace App\CommandHandlers;

use App\Commands\ItemDataStoreCommand;
use App\Repositories\ItemDataRepository;

class ItemDataStoreCommandHandler
{
    private ItemDataRepository $repository;

    public function __construct(ItemDataRepository $repository)
    {
        $this->repository = $repository;
    }

    public function handle(ItemDataStoreCommand $command): void
    {
        $this->repository->create([
            'uuid' => $command->getUuid(),
            'label' => $command->getLabel(),
            'content' => $command->getContent(),
        ]);
    }
}
