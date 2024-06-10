<?php

declare(strict_types=1);

namespace App\Listeners;

use App\Enums\CommonStatus;
use App\Events\ItemActivated;
use App\Events\ItemCache;
use App\Events\ItemCreated;
use App\Events\ItemDeleting;
use App\Events\ItemUpdated;
use App\Repositories\ItemRepository;
use Illuminate\Events\Dispatcher;
use Illuminate\Support\Facades\Log;

class ItemSubscriber
{
    private ItemRepository $repository;

    public function __construct(ItemRepository $repository)
    {
        $this->repository = $repository;
    }

    /**
     * Handle item created event.
     */
    public function handleItemCreated(ItemCreated $event): void
    {
        Log::channel('project')->info(
            __('log.item.created', [
                'item_id' => $event->item->id,
                'name' => $event->item->name,
                'action_by' => $event->item->created_by,
            ])
        );
    }

    /**
     * Handle item updated event.
     */
    public function handleItemUpdated(ItemUpdated $event): void
    {
        Log::channel('project')->info(
            __('log.item.updated', [
                'item_id' => $event->item->id,
                'action_by' => $event->item->updated_by,
            ]),
            $event->item->getFillableChanges()
        );

        if ($event->item->wasChanged('status') && $event->item->status == CommonStatus::ACTIVE) {
            ItemActivated::dispatch($event->item);
        }
    }

    /**
     * Handle item activated event.
     */
    public function handleItemActivated(ItemActivated $event): void
    {
        Log::channel('project')->info(
            __('log.item.activated', [
                'item_id' => $event->item->id,
                'action_by' => $event->item->updated_by,
            ])
        );
    }

    /**
     * Handle item deleting event.
     */
    public function handleItemDeleting(ItemDeleting $event): void
    {
        Log::channel('project')->info(
            __('log.item.deleting', [
                'item_id' => $event->item->id,
                'action_by' => auth()->id(),
            ])
        );
    }

    /**
     * Handle cache on item change event.
     */
    public function handleItemCache(ItemCache $event): void
    {
        $this->repository
            ->initCacheKey()
            ->addCachePiece($event->item->id)
            ->removeCacheContent();
    }

    /**
     * Register the listeners for the subscriber.
     */
    public function subscribe(Dispatcher $events): void
    {
        $events->listen(
            ItemCreated::class,
            [ItemSubscriber::class, 'handleItemCreated']
        );

        $events->listen(
            ItemUpdated::class,
            [ItemSubscriber::class, 'handleItemUpdated']
        );

        $events->listen(
            ItemActivated::class,
            [ItemSubscriber::class, 'handleItemActivated']
        );

        $events->listen(
            ItemDeleting::class,
            [ItemSubscriber::class, 'handleItemDeleting']
        );

        $events->listen(
            ItemCache::class,
            [ItemSubscriber::class, 'handleItemCache']
        );
    }
}
