<?php

namespace App\Interfaces;

use App\Models\Project;

interface ProjectRepositoryInterface
{
    public function create(array $data): Project;
    public function update(Project $model, array $data): bool;
    public function delete(Project $model): bool;
}
