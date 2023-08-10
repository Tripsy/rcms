<?php

declare(strict_types=1);

namespace App\Repositories;

use App\Models\Item;
use App\Repositories\Interfaces\ItemRepositoryInterface;

class ItemRepository implements ItemRepositoryInterface
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

    public function getAll(): array
    {
        return Item::all()->toArray();
    }

    public function findByUuid(string $uuid): ?Item
    {
        return Item::query()
            ->uuid($uuid)
            ->firstOrFail();
    }
}
