<?php

declare(strict_types=1);

namespace App\Observers;

use App\Events\ItemCache;
use App\Events\ItemCreated;
use App\Events\ItemDeleting;
use App\Events\ItemUpdated;
use App\Models\Item;
use App\Observers\Traits\StandardCreating;
use App\Observers\Traits\StandardUpdating;

class ItemObserver
{
    use StandardCreating;
    use StandardUpdating;

    /**
     * Handle the Model "created" event.
     */
    public function created(Item $item): void
    {
        ItemCreated::dispatch($item);
    }

    /**
     * Handle the Model "updated" event.
     *
     * When issuing an update or delete query via Eloquent, the saved, updated, deleting, and deleted model events
     * will not be dispatched for the affected models. This is because the models are never actually retrieved when
     * performing mass updates or deletes.
     */
    public function updated(Item $item): void
    {
        ItemUpdated::dispatch($item);
        ItemCache::dispatch($item);
    }

    /**
     * Handle the Model "deleting" event.
     */
    public function deleting(Item $item): void
    {
        ItemDeleting::dispatch($item);
        ItemCache::dispatch($item);
    }
}
