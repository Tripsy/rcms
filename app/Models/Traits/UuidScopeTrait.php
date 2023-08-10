<?php

namespace App\Models\Traits;

use Illuminate\Database\Eloquent\Builder;

trait UuidScopeTrait
{
    /**
     * Scope a query to select entries having selected uuid
     */
    public function scopeUuid(Builder $query, string $uuid): void
    {
        $query->where('uuid', $uuid);
    }
}
