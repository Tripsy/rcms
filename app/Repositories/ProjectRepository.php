<?php

declare(strict_types=1);

namespace App\Repositories;

use App\Enums\CommonStatus;
use App\Models\Project;
use App\Repositories\Interfaces\ProjectRepositoryInterface;
use Illuminate\Database\Eloquent\Model;

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

    public function findByAuthority(string $authority_name, string $authority_key): Model
    {
        return Project::query()
            ->where('authority_name', $authority_name)
            ->where('authority_key', $authority_key)
            ->firstOrFail();
    }

    public function findById(int $id): ?Project
    {
        return Project::query()
            ->id($id)
            ->firstOrFail();
    }

    public function isUnique(string $authority_name, string $name, int $id = null): bool
    {
        $project = Project::query()
            ->where('authority_name', $authority_name)
            ->where('name', $name);

        if ($id) {
            $project->where('id', '<>', $id);
        }

        return !$project->first();
    }

    public function showData(int $id): array
    {
        $project = $this->findById($id);

        return [
            'name' => $project->name,
            'authority_name' => $project->authority_name,
            'authority_key' => $project->authority_key,
            'status' => CommonStatus::from($project->status)->text(),
        ];
    }
}
