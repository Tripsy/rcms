<?php

declare(strict_types=1);

namespace App\Repositories;

use App\Interfaces\ItemRepositoryInterface;
use App\Models\Item;

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
            ->where('uuid', $uuid)
            ->firstOrFail();
    }
}
