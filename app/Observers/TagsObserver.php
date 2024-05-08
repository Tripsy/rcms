<?php

declare(strict_types=1);

namespace App\Observers;

use App\Events\TagsCache;
use App\Events\TagsCreated;
use App\Events\TagsDeleting;
use App\Events\TagsUpdated;
use App\Models\Tags;
use App\Observers\Traits\StandardCreating;
use App\Observers\Traits\StandardUpdating;

class TagsObserver
{
    use StandardCreating;
    use StandardUpdating;

    /**
     * Handle the Model "created" event.
     */
    public function created(Tags $tags): void
    {
        TagsCreated::dispatch($tags);
    }

    /**
     * Handle the Model "updated" event.
     *
     * When issuing an update or delete query via Eloquent, the saved, updated, deleting, and deleted model events
     * will not be dispatched for the affected models. This is because the models are never actually retrieved when
     * performing mass updates or deletes.
     */
    public function updated(Tags $tags): void
    {
        TagsUpdated::dispatch($tags);
        TagsCache::dispatch($tags);
    }

    /**
     * Handle the Model "deleting" event.
     */
    public function deleting(Tags $tags): void
    {
        TagsDeleting::dispatch($tags);
        TagsCache::dispatch($tags);
    }
}
