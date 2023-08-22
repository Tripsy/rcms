<?php

declare(strict_types=1);

namespace App\Queries;

use App\Models\ProjectPermission;

class ProjectPermissionUpdateQuery extends AbstractUpdateQuery
{
    public function __construct()
    {
        $this->query = ProjectPermission::query();
    }
}
