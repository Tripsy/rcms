<?php

declare(strict_types=1);

namespace App\Observers;

use App\Events\ItemContentCache;
use App\Events\ItemContentCreated;
use App\Events\ItemContentDeleting;
use App\Events\ItemContentUpdated;
use App\Models\ItemContent;
use App\Observers\Traits\StandardCreating;
use App\Observers\Traits\StandardUpdating;

class ItemContentObserver
{
    use StandardCreating;
    use StandardUpdating;

    /**
     * Handle the Model "created" event.
     */
    public function created(ItemContent $itemContent): void
    {
        ItemContentCreated::dispatch($itemContent);
    }

    /**
     * Handle the Model "updated" event.
     *
     * When issuing an update or delete query via Eloquent, the saved, updated, deleting, and deleted model events
     * will not be dispatched for the affected models. This is because the models are never actually retrieved when
     * performing mass updates or deletes.
     */
    public function updated(ItemContent $itemContent): void
    {
        ItemContentUpdated::dispatch($itemContent);
        ItemContentCache::dispatch($itemContent);
    }

    /**
     * Handle the Model "deleting" event.
     */
    public function deleting(ItemContent $itemContent): void
    {
        ItemContentDeleting::dispatch($itemContent);
        ItemContentCache::dispatch($itemContent);
    }
}
