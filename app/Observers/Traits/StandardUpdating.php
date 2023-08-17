<?php

declare(strict_types=1);

namespace App\Observers\Traits;

use Illuminate\Database\Eloquent\Model;

trait StandardUpdating
{
    /**
     * Handle the Model "updating" event.
     */
    public function updating(Model $model): void
    {
        $model->updated_by = auth()->id();
    }
}
