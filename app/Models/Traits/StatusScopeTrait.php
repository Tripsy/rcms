<?php

namespace App\Models\Traits;

use Illuminate\Database\Eloquent\Builder;

trait StatusScopeTrait
{
    /**
     * Scope a query to select entries having selected status
     */
    public function scopeStatus(Builder $query, string $status): void
    {
        $query->where('status', $status);
    }
}
