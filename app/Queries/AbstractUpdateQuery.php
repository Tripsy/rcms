<?php

declare(strict_types=1);

namespace App\Queries;

use App\Exceptions\ActionException;
use App\Queries\Traits\FilterByIdQueryTrait;
use Illuminate\Database\Eloquent\Builder;
use Symfony\Component\HttpFoundation\Response;

abstract class AbstractUpdateQuery
{
    use FilterByIdQueryTrait;

    protected Builder $query;

    protected bool $hasFilter = false;

    /**
     * @throws ActionException
     */
    public function updateFirst(array $data): void
    {
        if ($this->hasFilter === true) {
            $model = $this->query->firstOrFail();

            $model->update($data);
        } else {
            throw new ActionException(__('message.exception.update_without_filter'), Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
}
