<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Bus\CommandBus;
use App\Commands\AccountCreateCommand;
use App\Enums\AccountStatus;
use App\Queries\AccountQuery;
use Illuminate\Http\Request;

class AccountController extends Controller
{
    protected CommandBus $commandBus;

    public function __construct(CommandBus $commandBus)
    {
        $this->commandBus = $commandBus;
    }

    public function details(int $account_id, AccountQuery $query): array
    {
        return $query->getData($account_id);
    }

    public function store(Request $request): void
    {
        //TODO API
        //TODO verifications
        //TODO exceptions

        $email = $request->input('email');
        $status = AccountStatus::from($request->input('status'));

        $command = new AccountCreateCommand(
            $email,
            $status
        );

        $this->commandBus->execute($command);

        // Handle the response or redirect as needed
    }
}
