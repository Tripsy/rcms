<?php

declare(strict_types=1);

namespace App\Queries;

use App\Exceptions\ActionException;
use App\Queries\Traits\FilterByIdQueryTrait;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Symfony\Component\HttpFoundation\Response;

abstract class AbstractQuery
{
    use FilterByIdQueryTrait;

    /*
     * Used by update, delete to check if query filters are present to prevent accidental queries
     */
    protected bool $hasFilter = false;

    protected static string $model;

    protected Builder $query;

    public function __construct()
    {
        $this->query = $this->getModelClass()::query();
    }

    protected function getModelClass(): Model
    {
        return new static::$model;
    }

    public function asQuery(): Builder
    {
        return $this->query;
    }

    public function create(array $data): void
    {
        $this->getModelClass()::create($data);
    }

    public function get(int $page = 0, int $limit = 0): Collection
    {
        if ($page > 0) {
            $limit = $limit == 0 ? 10 : $limit;

            $this->query->skip($page * $limit - $limit);
        }

        if ($limit > 0) {
            $this->query->take($limit);
        }

        return $this->query->get();
    }

    public function firstOrFail(): Model
    {
        return $this->query->firstOrFail();
    }

    public function first(): ?Model
    {
        return $this->query->first();
    }

    public function isUnique(): bool
    {
        return ! $this->query->first();
    }

    /**
     * When using `with`, you should always include the id column and any relevant foreign key columns in
     * the list of columns you wish to retrieve.
     */
    public function withCreatedBy(array $fields = ['name']): self
    {
        array_unshift($fields, 'id');

        $columns = implode(',', array_unique($fields));

        $this->query->with('createdBy:'.$columns);

        return $this;
    }

    /**
     * When using `with`, you should always include the id column and any relevant foreign key columns in
     * the list of columns you wish to retrieve.
     */
    public function withUpdatedBy(array $fields = ['name']): self
    {
        array_unshift($fields, 'id');

        $columns = implode(',', array_unique($fields));

        $this->query->with('updatedBy:'.$columns);

        return $this;
    }

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

//    /**
//     * @throws ActionException
//     */
//    public function updateBulk(array $data): void
//    {
//        if ($this->hasFilter === true) {
//
//            $this->getModelClass()->update($data);
//        } else {
//            throw new ActionException(__('message.exception.update_without_filter'), Response::HTTP_INTERNAL_SERVER_ERROR);
//        }
//    }

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
