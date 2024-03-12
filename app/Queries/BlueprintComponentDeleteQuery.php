<?php

declare(strict_types=1);

namespace App\Queries;

use App\Models\BlueprintComponent;

class BlueprintComponentDeleteQuery extends AbstractDeleteQuery
{
    public function __construct()
    {
        $this->query = BlueprintComponent::query();
    }
}
