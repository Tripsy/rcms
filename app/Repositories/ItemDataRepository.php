<?php

declare(strict_types=1);

namespace App\Repositories;

use App\Enums\DefaultOption;
use App\Models\ItemContent;
use App\Repositories\Interfaces\ItemDataRepositoryInterface;

class ItemDataRepository implements ItemDataRepositoryInterface
{
    public function create(array $data): ItemContent
    {
        $itemData = ItemContent::query()
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

        return ItemContent::create($data);
    }

    public function delete(ItemContent $model): bool
    {
        return $model->delete();
    }

    public function setAsInactive(ItemContent $model): bool
    {
        return $model->update([
            'is_active' => DefaultOption::NO,
        ]);
    }
}
