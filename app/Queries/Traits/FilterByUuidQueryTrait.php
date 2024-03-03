<?php

declare(strict_types=1);

namespace App\Queries\Traits;

trait FilterByUuidQueryTrait
{
    public function filterByUuid(string $uuid, string $operator = '='): self
    {
        if ($uuid) {
            $this->query->where('uuid', $operator, $uuid);
        }

        return $this;
    }
}
