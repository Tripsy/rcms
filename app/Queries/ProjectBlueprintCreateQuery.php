<?php

declare(strict_types=1);

namespace App\Queries;

use App\Models\ProjectBlueprint;

class ProjectBlueprintCreateQuery extends AbstractCreateQuery
{
    public function __construct(ProjectBlueprint $model)
    {
        $this->model = $model;
    }
}
