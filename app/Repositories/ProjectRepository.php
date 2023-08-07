<?php

declare(strict_types=1);

namespace App\Repositories;

use App\Interfaces\ProjectRepositoryInterface;
use App\Models\Project;

class ProjectRepository implements ProjectRepositoryInterface
{
    public function create(array $data): Project
    {
        return Project::create($data);
    }

    public function update(Project $model, array $data): bool
    {
        return $model->update($data);
    }

    public function delete(Project $model): bool
    {
        return $model->delete();
    }
}
