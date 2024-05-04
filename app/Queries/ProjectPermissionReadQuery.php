<?php

declare(strict_types=1);

namespace App\Queries;

use App\Models\ProjectPermission;
use App\Queries\Traits\FilterByStatusQueryTrait;

class ProjectPermissionReadQuery extends AbstractReadQuery
{
    use FilterByStatusQueryTrait;

    public function __construct(ProjectPermission $model)
    {
        $this->model = $model;

        parent::__construct();
    }

    public function filterByProjectId(int $project_id): self
    {
        return $this->filterBy('project_id', $project_id);
    }

    public function filterByUserId(int $user_id): self
    {
        return $this->filterBy('user_id', $user_id);
    }

    public function filterByUserName(string $user_name, string $operator = '='): self
    {
        if ($user_name) {
            $this->query->whereHas('user', function ($query) use ($user_name, $operator) {
                $query->where('name', $operator, $user_name);
            });
        }

        return $this;
    }

    public function filterByRole(string $role): self
    {
        return $this->filterBy('role', $role);
    }

    public function withUser(array $fields = ['name']): self
    {
        array_unshift($fields, 'id');

        $columns = implode(',', array_unique($fields));

        $this->query->with('user:'.$columns);

        return $this;
    }
}
