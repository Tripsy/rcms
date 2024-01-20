<?php

declare(strict_types=1);

namespace App\Queries;

use App\Enums\CommonStatus;
use App\Models\Project;
use App\Queries\Traits\FilterByStatusQueryTrait;
use Illuminate\Database\Eloquent\Builder;

class ProjectReadQuery extends AbstractReadQuery
{
    use FilterByStatusQueryTrait;

    public function __construct(Project $model)
    {
        $this->model = $model;

        parent::__construct();
    }

    public function whereHasPermission(): self
    {
        $this->query->whereHas('permissions', function (Builder $query) {
            $query->where('status', CommonStatus::ACTIVE);
        });

        return $this;
    }

    public function filterByName(string $name, string $operator = '='): self
    {
        if ($name) {
            $this->query->where('name', $operator, $name);
        }

         return $this;
    }

    public function filterByAuthorityName(string $authority_name, string $operator = '='): self
    {
        if ($authority_name) {
            $this->query->where('authority_name', $operator, $authority_name);
        }

         return $this;
    }

    public function filterByAuthorityKey(string $authority_key, string $operator = '='): self
    {
        if ($authority_key) {
            $this->query->where('authority_key', $operator, $authority_key);
        }

         return $this;
    }
}
