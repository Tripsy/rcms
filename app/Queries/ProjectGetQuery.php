<?php

declare(strict_types=1);

namespace App\Queries;

use App\Enums\CommonStatus;
use App\Models\Project;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;

class ProjectGetQuery
{
    private Builder $query;

    public function __construct()
    {
        $this->query = Project::query();
    }

    public function whereHasPermission(): self
    {
        $this->query->whereHas('permissions', function (Builder $query) {
            $query->where('status', CommonStatus::ACTIVE);
        });

        return $this;
    }

    public function filterByAuthorityName(string $authority_name): self
    {
        if ($authority_name) {
            $this->query->where('authority_name', $authority_name);
        }

         return $this;
    }

    public function filterByStatus(string $status): self
    {
        if ($status) {
            $this->query->where('status', $status);
        }

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
}
