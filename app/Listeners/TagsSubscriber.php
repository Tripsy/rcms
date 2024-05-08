<?php

declare(strict_types=1);

namespace App\Listeners;

use App\Enums\CommonStatus;
use App\Events\TagsActivated;
use App\Events\TagsCache;
use App\Events\TagsCreated;
use App\Events\TagsDeleting;
use App\Events\TagsUpdated;
use App\Repositories\TagsRepository;
use Illuminate\Events\Dispatcher;
use Illuminate\Support\Facades\Log;

class TagsSubscriber
{
    private TagsRepository $repository;

    public function __construct(TagsRepository $repository)
    {
        $this->repository = $repository;
    }

    /**
     * Handle tags created event.
     */
    public function handleTagsCreated(TagsCreated $event): void
    {
        Log::channel('project')->info(
            __('log.tags.created', [
                'project_id' => $event->tags->project_id,
                'tags_id' => $event->tags->id,
                'name' => $event->tags->name,
                'action_by' => $event->tags->created_by,
            ])
        );
    }

    /**
     * Handle tags updated event.
     */
    public function handleTagsUpdated(TagsUpdated $event): void
    {
        Log::channel('project')->info(
            __('log.tags.updated', [
                'project_id' => $event->tags->project_id,
                'tags_id' => $event->tags->id,
                'action_by' => $event->tags->updated_by,
            ]),
            $event->tags->getFillableChanges()
        );

        if ($event->tags->wasChanged('status') && $event->tags->status == CommonStatus::ACTIVE) {
            TagsActivated::dispatch($event->tags);
        }
    }

    /**
     * Handle tags activated event.
     */
    public function handleTagsActivated(TagsActivated $event): void
    {
        Log::channel('project')->info(
            __('log.tags.activated', [
                'project_id' => $event->tags->project_id,
                'tags_id' => $event->tags->id,
                'action_by' => $event->tags->updated_by,
            ])
        );
    }

    /**
     * Handle tags deleting event.
     */
    public function handleTagsDeleting(TagsDeleting $event): void
    {
        Log::channel('project')->info(
            __('log.tags.deleting', [
                'project_id' => $event->tags->project_id,
                'tags_id' => $event->tags->id,
                'action_by' => auth()->id(),
            ])
        );
    }

    /**
     * Handle cache on tags change event.
     */
    public function handleTagsCache(TagsCache $event): void
    {
        $this->repository
            ->initCacheKey()
            ->addCachePiece($event->tags->id)
            ->removeCacheContent();
    }

    /**
     * Register the listeners for the subscriber.
     */
    public function subscribe(Dispatcher $events): void
    {
        $events->listen(
            TagsCreated::class,
            [TagsSubscriber::class, 'handleTagsCreated']
        );

        $events->listen(
            TagsUpdated::class,
            [TagsSubscriber::class, 'handleTagsUpdated']
        );

        $events->listen(
            TagsActivated::class,
            [TagsSubscriber::class, 'handleTagsActivated']
        );

        $events->listen(
            TagsDeleting::class,
            [TagsSubscriber::class, 'handleTagsDeleting']
        );

        $events->listen(
            TagsCache::class,
            [TagsSubscriber::class, 'handleTagsCache']
        );
    }
}
