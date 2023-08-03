<?php

declare(strict_types=1);

namespace App\CommandHandlers;

use App\Commands\AccountStoreCommand;
use App\Repositories\AccountRepository;

class AccountStoreCommandHandler
{
    private AccountRepository $repository;

    public function __construct(AccountRepository $repository)
    {
        $this->repository = $repository;
    }

    public function handle(AccountStoreCommand $command): void
    {
        $this->repository->create([
            'email' => $command->getEmail(),
            'status' => $command->getStatus(),
        ]);

        // Perform any additional operations or business logic related to user creation
        //TODO
    }
}
