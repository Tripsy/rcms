<?php

declare(strict_types=1);

namespace App\CommandHandlers;

use App\Commands\AccountCreateCommand;
use App\Repositories\AccountRepository;

class AccountCreateCommandHandler
{
    private AccountRepository $accountRepository;

    public function __construct(AccountRepository $accountRepository)
    {
        $this->accountRepository = $accountRepository;
    }

    public function handle(AccountCreateCommand $command): void
    {
        // Handle the creation of a new user using the command data
        // For example, create a new user in the database
        $account = $this->accountRepository->create([
            'email' => $command->getEmail(),
            'status' => $command->getStatus(),
        ]);

        // Perform any additional operations or business logic related to user creation
        //TODO
    }
}
