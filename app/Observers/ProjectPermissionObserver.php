<?php

declare(strict_types=1);

namespace App\Observers;

use App\Events\ProjectCache;
use App\Events\ProjectPermissionCache;
use App\Events\ProjectPermissionCreated;
use App\Events\ProjectPermissionDeleting;
use App\Events\ProjectPermissionUpdated;
use App\Models\ProjectPermission;
use App\Observers\Traits\StandardCreating;
use App\Observers\Traits\StandardUpdating;

class ProjectPermissionObserver
{
    use StandardCreating;
    use StandardUpdating;

    /**
     * Handle the Model "created" event.
     */
    public function created(ProjectPermission $permission): void
    {
        ProjectPermissionCreated::dispatch($permission);
        ProjectPermissionCache::dispatch($permission);

        $project = $permission->project()->first();

        ProjectCache::dispatch($project);
    }

    /**
     * Handle the Model "updated" event.
     *
     * When issuing an update or delete query via Eloquent, the saved, updated, deleting, and deleted model events
     * will not be dispatched for the affected models. This is because the models are never actually retrieved when
     * performing mass updates or deletes.
     */
    public function updated(ProjectPermission $permission): void
    {
        ProjectPermissionUpdated::dispatch($permission);
        ProjectPermissionCache::dispatch($permission);

        $project = $permission->project()->first();

        ProjectCache::dispatch($project);
    }

    /**
     * Handle the Model "deleting" event.
     */
    public function deleting(ProjectPermission $permission): void
    {
        ProjectPermissionDeleting::dispatch($permission);
        ProjectPermissionCache::dispatch($permission);

        $project = $permission->project()->first();

        ProjectCache::dispatch($project);
    }
}
