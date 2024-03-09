<?php

declare(strict_types=1);

namespace App\Observers;

use App\Events\BlueprintComponentCache;
use App\Events\BlueprintComponentCreated;
use App\Events\BlueprintComponentDeleting;
use App\Events\BlueprintComponentUpdated;
use App\Models\BlueprintComponent;
use App\Observers\Traits\StandardCreating;
use App\Observers\Traits\StandardUpdating;

class BlueprintComponentObserver
{
    use StandardCreating;
    use StandardUpdating;

    /**
     * Handle the Model "created" event.
     */
    public function created(BlueprintComponent $permission): void
    {
        BlueprintComponentCreated::dispatch($permission);
    }

    /**
     * Handle the Model "updated" event.
     *
     * When issuing an update or delete query via Eloquent, the saved, updated, deleting, and deleted model events
     * will not be dispatched for the affected models. This is because the models are never actually retrieved when
     * performing mass updates or deletes.
     */
    public function updated(BlueprintComponent $permission): void
    {
        BlueprintComponentUpdated::dispatch($permission);
        BlueprintComponentCache::dispatch($permission);
    }

    /**
     * Handle the Model "deleting" event.
     */
    public function deleting(BlueprintComponent $permission): void
    {
        BlueprintComponentDeleting::dispatch($permission);
        BlueprintComponentCache::dispatch($permission);
    }
}
