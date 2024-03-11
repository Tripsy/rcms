<?php

declare(strict_types=1);

namespace App\Queries;

use App\Models\ProjectBlueprint;

class ProjectBlueprintDeleteQuery extends AbstractDeleteQuery
{
    public function __construct()
    {
        $this->query = ProjectBlueprint::query();
    }
}
