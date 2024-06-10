<?php

declare(strict_types=1);

namespace App\Queries;

use App\Models\Tags;
use App\Queries\Traits\FilterByNameQueryTrait;
use App\Queries\Traits\FilterByStatusQueryTrait;

class TagsQuery extends AbstractQuery
{
    use FilterByNameQueryTrait;
    use FilterByStatusQueryTrait;

    protected static string $model = Tags::class;

    public function __construct()
    {
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
