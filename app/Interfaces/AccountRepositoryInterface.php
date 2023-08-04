<?php

namespace App\Interfaces;

use App\Models\Account;

interface AccountRepositoryInterface
{
    public function create(array $data): Account;
    public function update(Account $model, array $data): bool;
    public function delete(Account $model): bool;
}
