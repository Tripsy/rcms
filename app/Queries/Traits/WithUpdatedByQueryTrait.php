<?php

declare(strict_types=1);

namespace App\Queries\Traits;

trait WithUpdatedByQueryTrait
{
    public function withUpdatedBy(array $fields = ['name']): self
    {
        array_unshift($fields,'id');

        $columns = implode(',', array_unique($fields));

        $this->query->with('updatedBy:'.$columns);

        return $this;
    }
}
