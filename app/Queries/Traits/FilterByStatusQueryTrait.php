<?php

declare(strict_types=1);

namespace App\Queries\Traits;

trait FilterByStatusQueryTrait
{
    public function filterByStatus(string $status, string $operator = '='): self
    {
        if ($status) {
            $this->query->where('status', $operator, $status);
        }

        return $this;
    }
}
