<?php

declare(strict_types=1);

namespace App\Queries\Traits;

trait FilterByIdQueryTrait
{
    public function filterById(int $id, string $operator = '='): self
    {
        $this->hasFilter = true;

        $this->query->where('id', $operator, $id);

        return $this;
    }
}
