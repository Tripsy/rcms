<?php

declare(strict_types=1);

namespace App\Queries;

use App\Models\Tag;
use App\Queries\Traits\FilterByNameQueryTrait;
use App\Queries\Traits\FilterByStatusQueryTrait;

class TagQuery extends AbstractQuery
{
    use FilterByNameQueryTrait;
    use FilterByStatusQueryTrait;

    protected static string $model = Tag::class;

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
