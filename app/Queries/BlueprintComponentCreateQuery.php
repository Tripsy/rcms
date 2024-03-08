<?php

declare(strict_types=1);

namespace App\Queries;

use App\Models\BlueprintComponent;

class BlueprintComponentCreateQuery extends AbstractCreateQuery
{
    public function __construct(BlueprintComponent $model)
    {
        $this->model = $model;
    }
}
