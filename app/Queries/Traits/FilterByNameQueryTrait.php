<?php

declare(strict_types=1);

namespace App\Queries\Traits;

trait FilterByNameQueryTrait
{
    public function filterByName(string $name, string $operator = '='): self
    {
        return $this->filterBy('name', $name, $operator);
    }
}
