<?php

declare(strict_types=1);

namespace App\Queries;

use App\Queries\Traits\FilterByIdQueryTrait;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;

abstract class AbstractReadQuery
{
    use FilterByIdQueryTrait;

    protected Model $model;

    protected Builder $query;

    public function __construct()
    {
        $this->query = $this->model::query();
    }

    public function get(int $page = 0, int $limit = 0): Collection
    {
        if ($page > 0) {
            $limit = $limit == 0 ? 10 : $limit;

            $this->query->skip($page * $limit - $limit);
        }

        if ($limit > 0) {
            $this->query->take($limit);
        }

        return $this->query->get();
    }

    public function firstOrFail(): Model
    {
        return $this->query->firstOrFail();
    }

    public function first(): Model
    {
        return $this->query->first();
    }

    public function isUnique(): bool
    {
        return !$this->query->first();
    }
}
