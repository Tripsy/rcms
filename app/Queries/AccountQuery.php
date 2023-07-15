<?php

declare(strict_types=1);

namespace App\Queries;

use App\Enums\AccountStatus;
use App\Repositories\AccountRepository;

class AccountQuery
{
    private AccountRepository $accountRepository;

    public function __construct(AccountRepository $accountRepository)
    {
        $this->accountRepository = $accountRepository;
    }

    public function getData(int $account_id): array
    {
        $account = $this->accountRepository->findById($account_id);

        return [
            'email' => $account->email,
            'status' => AccountStatus::from($account->status)->text(),
        ];
    }
}
