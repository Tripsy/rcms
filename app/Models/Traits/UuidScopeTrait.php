<?php

namespace App\Models\Traits;

use Illuminate\Database\Eloquent\Builder;

trait UuidScopesTrait
{
    /**
     * Scope a query to select items having selected uuid
     */
    public function scopeUuid(Builder $query, string $uuid): void
    {
        $query->where('uuid', $uuid);
    }

    /**
     * Scope a query to select items having selected status
     */
    public function scopeStatus(Builder $query, string $status): void
    {
        $query->where('status', $status);
    }
}
