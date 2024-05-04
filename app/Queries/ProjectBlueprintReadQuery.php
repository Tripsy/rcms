<?php

declare(strict_types=1);

namespace App\Queries;

use App\Models\ProjectBlueprint;
use App\Queries\Traits\FilterByDescriptionQueryTrait;
use App\Queries\Traits\FilterByNameQueryTrait;
use App\Queries\Traits\FilterByStatusQueryTrait;
use App\Queries\Traits\FilterByUuidQueryTrait;

class ProjectBlueprintReadQuery extends AbstractReadQuery
{
    use FilterByDescriptionQueryTrait;
    use FilterByNameQueryTrait;
    use FilterByStatusQueryTrait;
    use FilterByUuidQueryTrait;

    public function __construct(ProjectBlueprint $model)
    {
        $this->model = $model;

        parent::__construct();
    }

    public function filterByProjectId(int $project_id): self
    {
        return $this->filterBy('project_id', $project_id);
    }

    /**
     * When using `with`, you should always include the id column and any relevant foreign key columns in
     * the list of columns you wish to retrieve.
     */
    public function withComponents(array $fields = [
        'name',
        'description',
        'info',
        'component_type',
        'component_format',
        'type_options',
        'is_required',
        'status',
    ]): self
    {
        array_unshift($fields, 'id', 'project_blueprint_id');

        $columns = implode(',', array_unique($fields));

        $this->query->with('components:'.$columns);

        return $this;
    }
}
