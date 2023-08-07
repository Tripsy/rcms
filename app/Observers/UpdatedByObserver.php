<?php

namespace App\Observers;

use Illuminate\Database\Eloquent\Model;

class UpdatedByObserver
{
    protected int $user_id;

    public function __construct()
    {
        $this->user_id = 1; //auth()->id(); //TODO
    }

    /**
     * Handle the Model "updating" event.
     */
    public function updating(Model $model): void
    {
        $model->updated_by = $this->user_id;
    }
}
