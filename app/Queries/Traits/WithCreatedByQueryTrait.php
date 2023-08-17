<?php

declare(strict_types=1);

namespace App\Queries\Traits;

trait WithCreatedByQueryTrait
{
    public function withCreatedBy(array $fields = ['name']): self
    {
        array_unshift($fields,'id');

        $columns = implode(',', array_unique($fields));

        $this->query->with('createdBy:'.$columns);

        return $this;
    }
}
