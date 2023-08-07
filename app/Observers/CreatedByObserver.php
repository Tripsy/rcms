<?php

namespace App\Observers;

use Illuminate\Database\Eloquent\Model;

class CreatedByObserver
{
    protected int $user_id;

    public function __construct()
    {
        $this->user_id = 1; //auth()->id(); //TODO
    }

    /**
     * Handle the Model "creating" event.
     */
    public function creating(Model $model): void
    {
        $model->created_by = $this->user_id;
    }
}
