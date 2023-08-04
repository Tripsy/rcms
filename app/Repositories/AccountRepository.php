<?php

declare(strict_types=1);

namespace App\Repositories;

use App\Interfaces\AccountRepositoryInterface;
use App\Models\Account;

class AccountRepository implements AccountRepositoryInterface
{
    public function create(array $data): Account
    {
        return Account::create($data);
    }

    public function update(Account $model, array $data): bool
    {
        return $model->update($data);
    }

    public function delete(Account $model): bool
    {
        return $model->delete();
    }

//    public function findById(int $id): ?Account
//    {
//        return Account::find($id);
//    }
//
//    public function findByEmail(string $email): ?Account
//    {
//        return Account::where('email', $email)->first();
//    }
}
