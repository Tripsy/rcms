<?php

declare(strict_types=1);

namespace App\Repositories;

use App\Models\Item;

class ItemRepository
{
    public function create(array $data): Item
    {
        return Item::create($data);
    }

    public function update(Item $model, array $data): bool
    {
        return $model->update($data);
    }

    public function delete(Item $model): bool
    {
        return $model->delete();
    }

    public function findById(int $id): ?Item
    {
        return Item::find($id);
    }

    public function getAll(): array
    {
        return Item::all()->toArray();
    }
}
