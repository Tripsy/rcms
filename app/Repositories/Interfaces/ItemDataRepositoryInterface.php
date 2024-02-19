<?php

namespace App\Repositories\Interfaces;

use App\Models\ItemContent;

interface ItemDataRepositoryInterface
{
    public function create(array $data): ItemContent;

    public function delete(ItemContent $model): bool;
}
