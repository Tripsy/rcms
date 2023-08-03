<?php

declare(strict_types=1);

namespace App\Repositories;

use App\Enums\DefaultOption;
use App\Models\ItemData;

class ItemDataRepository
{
    public function create(array $data): ItemData
    {
        ItemData::query()
            ->uuid($data['uuid'])
            ->label($data['label'])
            ->update([
                'is_active' => DefaultOption::NO->value,
            ]);

        return ItemData::create($data);
    }

//    public function update(ItemData $model, array $data): bool
//    {
//        return $model->update($data);
//    }

    public function delete(ItemData $model): bool
    {
        return $model->delete();
    }

    public function findById(int $id): ?ItemData
    {
        return ItemData::find($id);
    }

    public function getAll(): array
    {
        return ItemData::all()->toArray();
    }
}
