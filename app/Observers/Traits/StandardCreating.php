<?php

declare(strict_types=1);

namespace App\Observers\Traits;

use Illuminate\Database\Eloquent\Model;

trait StandardCreating
{
    /**
     * Handle the Model "creating" event.
     */
    public function creating(Model $model): void
    {
        $model->created_by = auth()->id();
    }
}
