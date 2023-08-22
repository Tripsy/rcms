<?php

declare(strict_types=1);

namespace App\Queries;

use App\Models\ProjectPermission;

class ProjectPermissionDeleteQuery extends AbstractDeleteQuery
{
    public function __construct()
    {
        $this->query = ProjectPermission::query();
    }
}
