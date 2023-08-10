<?php

namespace App\Models\Traits;

use Illuminate\Database\Eloquent\Builder;

trait IdScopeTrait
{
    /**
     * Scope a query to select entry having selected id
     */
    public function scopeId(Builder $query, int $id): void
    {
        $query->where('id', $id);
    }
}
