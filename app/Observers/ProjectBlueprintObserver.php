<?php

declare(strict_types=1);

namespace App\Observers;

use App\Events\ProjectBlueprintCache;
use App\Events\ProjectBlueprintCreated;
use App\Events\ProjectBlueprintDeleting;
use App\Events\ProjectBlueprintUpdated;
use App\Models\ProjectBlueprint;
use App\Observers\Traits\StandardCreating;
use App\Observers\Traits\StandardUpdating;

class ProjectBlueprintObserver
{
    use StandardCreating;
    use StandardUpdating;

    /**
     * Handle the Model "created" event.
     */
    public function created(ProjectBlueprint $permission): void
    {
        ProjectBlueprintCreated::dispatch($permission);
    }

    /**
     * Handle the Model "updated" event.
     *
     * When issuing an update or delete query via Eloquent, the saved, updated, deleting, and deleted model events
     * will not be dispatched for the affected models. This is because the models are never actually retrieved when
     * performing mass updates or deletes.
     */
    public function updated(ProjectBlueprint $permission): void
    {
        ProjectBlueprintUpdated::dispatch($permission);
        ProjectBlueprintCache::dispatch($permission);
    }

    /**
     * Handle the Model "deleting" event.
     */
    public function deleting(ProjectBlueprint $permission): void
    {
        ProjectBlueprintDeleting::dispatch($permission);
        ProjectBlueprintCache::dispatch($permission);
    }
}
