<?php

declare(strict_types=1);

namespace App\Queries;

use App\Models\Tags;
use App\Queries\Traits\FilterByNameQueryTrait;
use App\Queries\Traits\FilterByStatusQueryTrait;

class TagsReadQuery extends AbstractReadQuery
{
    use FilterByNameQueryTrait;
    use FilterByStatusQueryTrait;

    public function __construct(Tags $model)
    {
        $this->model = $model;

        parent::__construct();
    }

    public function filterByProjectId(int $project_id): self
    {
        return $this->filterBy('project_id', $project_id);
    }

    public function isCategory(string $is_category): self
    {
        return $this->filterBy('is_category', $is_category);
    }
}
