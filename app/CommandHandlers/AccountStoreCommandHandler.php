<?php

declare(strict_types=1);

namespace App\CommandHandlers;

use App\Commands\AccountStoreCommand;
use App\Events\AccountCreated;
use App\Repositories\AccountRepository;
use Illuminate\Support\Facades\Event;

class AccountStoreCommandHandler
{
    private AccountRepository $accountRepository;

    public function __construct(AccountRepository $accountRepository)
    {
        $this->accountRepository = $accountRepository;
    }

    public function handle(AccountStoreCommand $command): void
    {
        $this->accountRepository->create([
            'email' => $command->getEmail(),
            'status' => $command->getStatus(),
        ]);

        // Perform any additional operations or business logic related to user creation
        //TODO
    }
}
