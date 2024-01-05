<?php

declare(strict_types=1);

namespace App\Queries;

use App\Models\Project;

class ProjectCreateQuery extends AbstractCreateQuery
{
    public function __construct(Project $model)
    {
        $this->model = $model;
    }
}
