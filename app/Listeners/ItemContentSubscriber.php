<?php

declare(strict_types=1);

namespace App\Listeners;

use App\Enums\CommonStatus;
use App\Events\ItemContentActivated;
use App\Events\ItemContentCache;
use App\Events\ItemContentCreated;
use App\Events\ItemContentDeleting;
use App\Events\ItemContentUpdated;
use App\Repositories\ItemContentRepository;
use Illuminate\Events\Dispatcher;
use Illuminate\Support\Facades\Log;

class ItemContentSubscriber
{
    private ItemContentRepository $repository;

    public function __construct(ItemContentRepository $repository)
    {
        $this->repository = $repository;
    }

    /**
     * Handle itemContent created event.
     */
    public function handleItemContentCreated(ItemContentCreated $event): void
    {
        Log::channel('project')->info(
            __('log.itemContent.created', [
                'itemContent_id' => $event->itemContent->id,
                'name' => $event->itemContent->name,
                'action_by' => $event->itemContent->created_by,
            ])
        );
    }

    /**
     * Handle itemContent updated event.
     */
    public function handleItemContentUpdated(ItemContentUpdated $event): void
    {
        Log::channel('project')->info(
            __('log.itemContent.updated', [
                'itemContent_id' => $event->itemContent->id,
                'action_by' => $event->itemContent->updated_by,
            ]),
            $event->itemContent->getFillableChanges()
        );

        if ($event->itemContent->wasChanged('status') && $event->itemContent->status == CommonStatus::ACTIVE) {
            ItemContentActivated::dispatch($event->itemContent);
        }
    }

    /**
     * Handle itemContent activated event.
     */
    public function handleItemContentActivated(ItemContentActivated $event): void
    {
        Log::channel('project')->info(
            __('log.itemContent.activated', [
                'itemContent_id' => $event->itemContent->id,
                'action_by' => $event->itemContent->updated_by,
            ])
        );
    }

    /**
     * Handle itemContent deleting event.
     */
    public function handleItemContentDeleting(ItemContentDeleting $event): void
    {
        Log::channel('project')->info(
            __('log.itemContent.deleting', [
                'itemContent_id' => $event->itemContent->id,
                'action_by' => auth()->id(),
            ])
        );
    }

    /**
     * Handle cache on itemContent change event.
     */
    public function handleItemContentCache(ItemContentCache $event): void
    {
        $this->repository
            ->initCacheKey()
            ->addCachePiece($event->itemContent->id)
            ->removeCacheContent();
    }

    /**
     * Register the listeners for the subscriber.
     */
    public function subscribe(Dispatcher $events): void
    {
        $events->listen(
            ItemContentCreated::class,
            [ItemContentSubscriber::class, 'handleItemContentCreated']
        );

        $events->listen(
            ItemContentUpdated::class,
            [ItemContentSubscriber::class, 'handleItemContentUpdated']
        );

        $events->listen(
            ItemContentActivated::class,
            [ItemContentSubscriber::class, 'handleItemContentActivated']
        );

        $events->listen(
            ItemContentDeleting::class,
            [ItemContentSubscriber::class, 'handleItemContentDeleting']
        );

        $events->listen(
            ItemContentCache::class,
            [ItemContentSubscriber::class, 'handleItemContentCache']
        );
    }
}
