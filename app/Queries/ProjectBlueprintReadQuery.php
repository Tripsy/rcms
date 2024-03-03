<?php

declare(strict_types=1);

namespace App\Queries;

use App\Models\ProjectBlueprint;
use App\Queries\Traits\FilterByStatusQueryTrait;
use App\Queries\Traits\FilterByUuidQueryTrait;

class ProjectBlueprintReadQuery extends AbstractReadQuery
{
    use FilterByUuidQueryTrait;
    use FilterByStatusQueryTrait;

    public function __construct(ProjectBlueprint $model)
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

    public function filterByDescription(string $description, string $operator = '='): self
    {
        if ($description) {
            $this->query->where('description', $operator, $description);
        }

        return $this;
    }

    public function filterByNotes(string $notes, string $operator = '='): self
    {
        if ($notes) {
            $this->query->where('notes', $operator, $notes);
        }

        return $this;
    }
}