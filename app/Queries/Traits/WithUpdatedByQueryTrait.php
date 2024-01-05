<?php

declare(strict_types=1);

namespace App\Queries\Traits;

trait WithUpdatedByQueryTrait
{
    /**
     * When using `with`, you should always include the id column and any relevant foreign key columns in the list of columns you wish to retrieve.
     */
    public function withUpdatedBy(array $fields = ['name']): self
    {
        array_unshift($fields,'id');

        $columns = implode(',', array_unique($fields));

        $this->query->with('updatedBy:'.$columns);

        return $this;
    }
}
