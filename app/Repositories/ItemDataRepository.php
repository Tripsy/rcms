<?php

declare(strict_types=1);

namespace App\Repositories;

use App\Enums\DefaultOption;
use App\Models\ItemData;
use App\Repositories\Interfaces\ItemDataRepositoryInterface;

class ItemDataRepository implements ItemDataRepositoryInterface
{
    public function create(array $data): ItemData
    {
        $itemData = ItemData::query()
            ->uuid($data['uuid'])
            ->label($data['label'])
            ->isActive()
            ->first();

        if ($itemData) {
            if ($itemData->content === $data['content']) {
                return $itemData;
            } else {
                $this->setAsInactive($itemData);
            }
        }

        return ItemData::create($data);
    }

    public function delete(ItemData $model): bool
    {
        return $model->delete();
    }

    public function setAsInactive(ItemData $model): bool
    {
        return $model->update([
            'is_active' => DefaultOption::NO
        ]);
    }
}
