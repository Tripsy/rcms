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

    public function filterByProjectId(int $project_id, string $operator = '='): self
    {
        if ($project_id) {
            $this->query->where('project_id', $operator, $project_id);
        }

         return $this;
    }

    public function filterByUserId(int $user_id, string $operator = '='): self
    {
        if ($user_id) {
            $this->query->where('user_id', $operator, $user_id);
        }

         return $this;
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

    public function filterByRole(string $role, string $operator = '='): self
    {
        if ($role) {
            $this->query->where('role', $operator, $role);
        }

        return $this;
    }

    public function withUser(array $fields = ['name']): self
    {
        array_unshift($fields,'id');

        $columns = implode(',', array_unique($fields));

        $this->query->with('user:'.$columns);

        return $this;
    }
}
