<?php

declare(strict_types=1);

namespace App\Queries;

use App\Exceptions\ActionException;
use App\Models\Project;
use Illuminate\Database\Eloquent\Builder;
use Symfony\Component\HttpFoundation\Response;

class ProjectUpdateQuery
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
    public function update(array $data): void
    {
        if ($this->hasFilter === true) {
            $this->query->update($data);
        } else {
            throw new ActionException(__('message.exception.update_without_filter'), Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
}
