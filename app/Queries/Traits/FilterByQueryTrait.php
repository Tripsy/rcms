<?php

declare(strict_types=1);

namespace App\Queries\Traits;

trait FilterByQueryTrait
{
    public function filterBy(string $column, string|int|null $value, string $operator = '='): self
    {
        if ($value) {
            $this->query->where($column, $operator, $value);
        }

        return $this;
    }
}
