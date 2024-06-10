<?php

declare(strict_types=1);

namespace App\Queries;

use App\Models\Item;
use App\Queries\Traits\FilterByDescriptionQueryTrait;
use App\Queries\Traits\FilterByStatusQueryTrait;
use App\Queries\Traits\FilterByUuidQueryTrait;

class ItemQuery extends AbstractQuery
{
    use FilterByDescriptionQueryTrait;
    use FilterByStatusQueryTrait;
    use FilterByUuidQueryTrait;

    protected static string $model = Item::class;

    public function __construct()
    {
        parent::__construct();
    }

    public function filterByProjectBlueprintId(int $project_blueprint_id): self
    {
        return $this->filterBy('project_blueprint_id', $project_blueprint_id);
    }
}
