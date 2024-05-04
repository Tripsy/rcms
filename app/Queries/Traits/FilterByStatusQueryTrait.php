<?php

declare(strict_types=1);

namespace App\Queries\Traits;

trait FilterByStatusQueryTrait
{
    use FilterByQueryTrait;

    public function filterByStatus(string $status): self
    {
        return $this->filterBy('status', $status, '=');
    }
}
