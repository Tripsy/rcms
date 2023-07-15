<?php

declare(strict_types=1);

namespace App\Repositories;

use App\Models\Account;

class AccountRepository
{
    public function create(array $data): Account
    {
        return Account::create($data);
    }

    public function update(Account $account, array $data): bool
    {
        return $account->update($data);
    }

    public function delete(Account $account): bool
    {
        return $account->delete();
    }

    public function findById(int $id): ?Account
    {
        return Account::find($id);
    }

    public function findByEmail(string $email): ?Account
    {
        return Account::where('email', $email)->first();
    }

    public function getAll(): array
    {
        return Account::all()->toArray();
    }
}
