<?php

declare(strict_types=1);

namespace App\Queries\Traits;

trait FilterByNameQueryTrait
{
    public function filterByName(string $name, string $operator = '='): self
    {
        if ($name) {
            $this->query->where('name', $operator, $name);
        }

        return $this;
    }
}
