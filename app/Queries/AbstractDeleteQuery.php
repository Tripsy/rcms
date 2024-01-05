<?php

declare(strict_types=1);

namespace App\Queries;

use App\Exceptions\ActionException;
use App\Queries\Traits\FilterByIdQueryTrait;
use Illuminate\Database\Eloquent\Builder;
use Symfony\Component\HttpFoundation\Response;

abstract class AbstractDeleteQuery
{
    use FilterByIdQueryTrait;

    protected Builder $query;

    protected bool $hasFilter = false;

    /**
     * @throws ActionException
     */
    public function deleteFirst(): void
    {
        if ($this->hasFilter === true) {
            $model = $this->query->firstOrFail();

            $model->delete();
        } else {
            throw new ActionException(__('message.exception.delete_without_filter'), Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
}
