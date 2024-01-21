<?php

declare(strict_types=1);

namespace App\Observers;

use App\Events\ProjectCache;
use App\Events\ProjectCreated;
use App\Events\ProjectUpdated;
use App\Models\Project;
use App\Observers\Traits\StandardCreating;
use App\Observers\Traits\StandardUpdating;

class ProjectObserver
{
    use StandardCreating;
    use StandardUpdating;

    /**
     * Handle the Model "created" event.
     */
    public function created(Project $project): void
    {
        ProjectCreated::dispatch($project);
        ProjectCache::dispatch($project);
    }

    /**
     * Handle the Model "updated" event.
     *
     * When issuing an update or delete query via Eloquent, the saved, updated, deleting, and deleted model events
     * will not be dispatched for the affected models. This is because the models are never actually retrieved when
     * performing mass updates or deletes.
     *
     */
    public function updated(Project $project): void
    {
        ProjectUpdated::dispatch($project);
        ProjectCache::dispatch($project);
    }
}
