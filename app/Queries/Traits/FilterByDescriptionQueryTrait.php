<?php

declare(strict_types=1);

namespace App\Queries\Traits;

trait FilterByDescriptionQueryTrait
{
    public function filterByDescription(string $description, string $operator = '='): self
    {
        if ($description) {
            $this->query->where('description', $operator, $description);
        }

        return $this;
    }
}
