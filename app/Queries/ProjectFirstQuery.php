<?php

declare(strict_types=1);

namespace App\Queries;

use App\Models\Project;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

class ProjectFirstQuery
{
    private Builder $query;

    public function __construct()
    {
        $this->query = Project::query();
    }

    public function filterById(int $id): self
    {
        $this->query->id($id);

        return $this;
    }

    public function withCreatedBy(array $fields = ['name', 'email']): self
    {
        array_unshift($fields,'id');

        $columns = implode(',', array_unique($fields));

        $this->query->with('createdBy:'.$columns);

        return $this;
    }

    public function withUpdatedBy(array $fields = ['name', 'email']): self
    {
        array_unshift($fields,'id');

        $columns = implode(',', array_unique($fields));

        $this->query->with('updatedBy:'.$columns);

        return $this;
    }

    public function firstOrFail(): Model
    {
        return $this->query->firstOrFail();
    }

    public function first(): Model
    {
        return $this->query->first();
    }
}
