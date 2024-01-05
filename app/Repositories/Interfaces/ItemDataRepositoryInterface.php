<?php

namespace App\Repositories\Interfaces;

use App\Models\ItemData;

interface ItemDataRepositoryInterface
{
    public function create(array $data): ItemData;
    public function delete(ItemData $model): bool;
}
