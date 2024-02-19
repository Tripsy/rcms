<?php

declare(strict_types=1);

namespace App\Repositories;

use App\Models\ProjectItem;
use App\Repositories\Interfaces\ItemRepositoryInterface;

class ItemRepository implements ItemRepositoryInterface
{
    public function create(array $data): ProjectItem
    {
        return ProjectItem::create($data);
    }

    public function update(ProjectItem $model, array $data): bool
    {
        return $model->update($data);
    }

    public function delete(ProjectItem $model): bool
    {
        return $model->delete();
    }

    public function getAll(): array
    {
        return ProjectItem::all()->toArray();
    }

    public function findByUuid(string $uuid): ?ProjectItem
    {
        return ProjectItem::query()
            ->uuid($uuid)
            ->firstOrFail();
    }
}
