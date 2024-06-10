<?php

declare(strict_types=1);

namespace App\Queries;

use App\Models\BlueprintComponent;
use App\Queries\Traits\FilterByDescriptionQueryTrait;
use App\Queries\Traits\FilterByNameQueryTrait;
use App\Queries\Traits\FilterByQueryTrait;
use App\Queries\Traits\FilterByStatusQueryTrait;

class BlueprintComponentQuery extends AbstractQuery
{
    use FilterByDescriptionQueryTrait;
    use FilterByNameQueryTrait;
    use FilterByQueryTrait;
    use FilterByStatusQueryTrait;

    protected static string $model = BlueprintComponent::class;

    public function __construct()
    {
        parent::__construct();
    }

    public function filterByProjectBlueprintId(int $project_blueprint_id): self
    {
        return $this->filterBy('project_blueprint_id', $project_blueprint_id);
    }

    public function filterByInfo(string $info, string $operator = '='): self
    {
        return $this->filterBy('info', $info, $operator);
    }

    public function filterByComponentType(string $component_type): self
    {
        return $this->filterBy('component_type', $component_type);
    }

    public function filterByComponentFormat(string $component_format): self
    {
        return $this->filterBy('component_format', $component_format);
    }

    public function isRequired(string $is_required): self
    {
        return $this->filterBy('is_required', $is_required);
    }
}
