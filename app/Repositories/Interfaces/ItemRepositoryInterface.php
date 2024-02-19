<?php

namespace App\Repositories\Interfaces;

use App\Models\ProjectItem;

interface ItemRepositoryInterface
{
    public function create(array $data): ProjectItem;

    public function update(ProjectItem $model, array $data): bool;

    public function delete(ProjectItem $model): bool;

    public function getAll(): array;

    public function findByUuid(string $uuid): ?ProjectItem;
}
