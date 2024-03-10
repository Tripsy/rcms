<?php

declare(strict_types=1);

namespace App\Queries;

use App\Models\BlueprintComponent;
use App\Queries\Traits\FilterByDescriptionQueryTrait;
use App\Queries\Traits\FilterByNameQueryTrait;
use App\Queries\Traits\FilterByStatusQueryTrait;

class BlueprintComponentReadQuery extends AbstractReadQuery
{
    use FilterByDescriptionQueryTrait;
    use FilterByNameQueryTrait;
    use FilterByStatusQueryTrait;

    public function __construct(BlueprintComponent $model)
    {
        $this->model = $model;

        parent::__construct();
    }

    public function filterByProjectBlueprintId(int $project_blueprint_id, string $operator = '='): self
    {
        if ($project_blueprint_id) {
            $this->query->where('project_blueprint_id', $operator, $project_blueprint_id);
        }

        return $this;
    }

    public function filterByComponentType(string $component_type, string $operator = '='): self
    {
        if ($component_type) {
            $this->query->where('component_type', $operator, $component_type);
        }

        return $this;
    }

    public function filterByComponentFormat(string $component_format, string $operator = '='): self
    {
        if ($component_format) {
            $this->query->where('component_format', $operator, $component_format);
        }

        return $this;
    }

    public function isRequired(string $is_required): self
    {
        if ($is_required) {
            $this->query->where('is_required', $is_required);
        }

        return $this;
    }
}
