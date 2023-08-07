<?php

declare(strict_types=1);

namespace App\Queries;

use App\Enums\ProjectStatus;
use App\Interfaces\ProjectRepositoryInterface;

class ProjectQuery
{
    private ProjectRepositoryInterface $projectRepository;

    public function __construct(ProjectRepositoryInterface $projectRepository)
    {
        $this->projectRepository = $projectRepository;
    }

    public function getData(int $account_id): array
    {
        $project = $this->projectRepository->findById($account_id);

        return [
            'name' => $project->name,
            'authority_name' => $project->authority_name,
            'authority_key' => $project->authority_key,
            'status' => ProjectStatus::from($project->status)->text(),
        ];
    }
}
