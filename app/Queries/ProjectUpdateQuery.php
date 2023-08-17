<?php

declare(strict_types=1);

namespace App\Queries;

use App\Models\Project;

class ProjectUpdateQuery extends AbstractUpdateQuery
{
    public function __construct()
    {
        $this->query = Project::query();
    }
}
