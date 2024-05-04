<?php

declare(strict_types=1);

namespace App\Queries\Traits;

trait FilterByDescriptionQueryTrait
{
    use FilterByQueryTrait;

    public function filterByDescription(string $description, string $operator = '='): self
    {
        return $this->filterBy('description', $description, $operator);
    }
}
