<?php

namespace App\Models\Traits;

use Illuminate\Database\Eloquent\Builder;

trait StatusScopeTrait
{
    /**
     * Scope a query to select items having selected status
     */
    public function scopeStatus(Builder $query, string $status): void
    {
        $query->where('status', $status);
    }
}
