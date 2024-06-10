<?php

declare(strict_types=1);

namespace App\Queries;

use App\Enums\CommonStatus;
use App\Enums\DefaultOption;
use App\Models\ItemContent;
use App\Queries\Traits\FilterByNameQueryTrait;
use App\Queries\Traits\FilterByStatusQueryTrait;
use Illuminate\Database\Eloquent\Builder;

class ItemContentQuery  extends AbstractQuery
{
    use FilterByStatusQueryTrait;
    use FilterByNameQueryTrait;

    public function __construct(ItemContent $model)
    {
        $this->model = $model;

        parent::__construct();
    }

    public function filterByItemId(int $item_id): self
    {
        $this->hasFilter = true;

        return $this->filterBy('item_id', $item_id);
    }

    public function filterBlueprintComponentId(int $blueprint_component_id): self
    {
        return $this->filterBy('blueprint_component_id', $blueprint_component_id);
    }

    public function isActive(): self
    {
        return $this->filterBy('is_active', DefaultOption::YES->value);
    }
}
