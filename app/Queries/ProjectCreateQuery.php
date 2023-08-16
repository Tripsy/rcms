<?php

declare(strict_types=1);

namespace App\Queries;

use App\Models\Project;

class ProjectCreateQuery
{
    public function create(array $data): void
    {
        Project::create($data);
    }
}
