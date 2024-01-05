<?php

declare(strict_types=1);

namespace App\Queries;

use App\Models\ProjectPermission;

class ProjectPermissionCreateQuery extends AbstractCreateQuery
{
    public function __construct(ProjectPermission $model)
    {
        $this->model = $model;
    }
}
