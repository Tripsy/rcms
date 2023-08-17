<?php

declare(strict_types=1);

namespace App\Queries;

use Illuminate\Database\Eloquent\Model;

abstract class AbstractCreateQuery
{
    protected Model $model;

    public function create(array $data): void
    {
        $this->model::create($data);
    }
}
