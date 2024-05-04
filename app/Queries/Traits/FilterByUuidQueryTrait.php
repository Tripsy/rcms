<?php

declare(strict_types=1);

namespace App\Queries\Traits;

trait FilterByUuidQueryTrait
{
    use FilterByQueryTrait;

    public function filterByUuid(string $uuid, string $operator = '='): self
    {
        return $this->filterBy('uuid', $uuid, $operator);
    }
}
