<?php

declare(strict_types=1);

namespace App\Queries;

use App\Models\BlueprintComponent;

class BlueprintComponentUpdateQuery extends AbstractUpdateQuery
{
    public function __construct()
    {
        $this->query = BlueprintComponent::query();
    }
}
