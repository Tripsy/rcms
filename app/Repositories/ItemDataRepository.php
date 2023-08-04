<?php

declare(strict_types=1);

namespace App\Repositories;

use App\Enums\DefaultOption;
use App\Interfaces\ItemDataRepositoryInterface;
use App\Models\ItemData;

class ItemDataRepository implements ItemDataRepositoryInterface
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

    public function delete(ItemData $model): bool
    {
        return $model->delete();
    }

//    public function findById(int $id): ?ItemData
//    {
//        return ItemData::find($id);
//    }
}
