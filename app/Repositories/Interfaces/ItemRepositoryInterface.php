<?php

namespace App\Repositories\Interfaces;

use App\Models\Item;

interface ItemRepositoryInterface
{
    public function create(array $data): Item;

    public function update(Item $model, array $data): bool;

    public function delete(Item $model): bool;

    public function getAll(): array;

    public function findByUuid(string $uuid): ?Item;
}
