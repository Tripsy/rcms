<?php

declare(strict_types=1);

namespace App\Queries;

use App\Exceptions\ActionException;
use App\Models\Project;
use Illuminate\Database\Eloquent\Builder;
use Symfony\Component\HttpFoundation\Response;

class ProjectDeleteQuery
{
    private Builder $query;

    private bool $hasFilter = false;

    public function __construct()
    {
        $this->query = Project::query();
    }

    public function filterById(int $id): self
    {
        $this->hasFilter = true;

        $this->query->id($id);

        return $this;
    }

    /**
     * @throws ActionException
     */
    public function delete(): void
    {
        if ($this->hasFilter === true) {
            $this->query->delete();
        } else {
            throw new ActionException(__('message.exception.delete_without_filter'), Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
}
