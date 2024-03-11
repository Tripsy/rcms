<?php

declare(strict_types=1);

namespace App\Queries;

use App\Models\ProjectBlueprint;

class ProjectBlueprintUpdateQuery extends AbstractUpdateQuery
{
    public function __construct()
    {
        $this->query = ProjectBlueprint::query();
    }
}
