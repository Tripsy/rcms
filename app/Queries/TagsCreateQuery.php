<?php

declare(strict_types=1);

namespace App\Queries;

use App\Models\Tags;

class TagsCreateQuery  extends AbstractCreateQuery
{
    public function __construct(Tags $model)
    {
        $this->model = $model;
    }
}
