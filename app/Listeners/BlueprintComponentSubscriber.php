<?php

declare(strict_types=1);

namespace App\Listeners;

use App\Enums\CommonStatus;
use App\Events\BlueprintComponentActivated;
use App\Events\BlueprintComponentCache;
use App\Events\BlueprintComponentCreated;
use App\Events\BlueprintComponentDeleting;
use App\Events\BlueprintComponentUpdated;
use App\Repositories\BlueprintComponentRepository;
use Illuminate\Events\Dispatcher;
use Illuminate\Support\Facades\Log;

class BlueprintComponentSubscriber
{
    private BlueprintComponentRepository $repository;

    public function __construct(BlueprintComponentRepository $repository)
    {
        $this->repository = $repository;
    }

    /**
     * Handle project created event.
     */
    public function handleProjectBlueprintCreated(BlueprintComponentCreated $event): void
    {
        Log::channel('project')->info(__('log.blueprint_component.created', [
            'project_blueprint_id' => $event->component->project_blueprint_id,
            'blueprint_component_id' => $event->component->id,
            'action_by' => $event->component->created_by,
        ]));
    }

    /**
     * Handle project updated event.
     */
    public function handleBlueprintComponentUpdated(BlueprintComponentUpdated $event): void
    {
        Log::channel('project')->info(
            __('log.blueprint_component.updated', [
                'project_blueprint_id' => $event->component->project_blueprint_id,
                'blueprint_component_id' => $event->component->id,
                'action_by' => $event->component->updated_by,
            ]),
            $event->blueprint->getFillableChanges()
        );

        if ($event->blueprint->wasChanged('status') && $event->blueprint->status == CommonStatus::ACTIVE) {
            BlueprintComponentActivated::dispatch($event->blueprint);
        }
    }

    /**
     * Handle project activated event.
     */
    public function handleBlueprintComponentActivated(BlueprintComponentActivated $event): void
    {
        Log::channel('project')->info(__('log.blueprint_component.activated', [
            'project_blueprint_id' => $event->component->project_blueprint_id,
            'blueprint_component_id' => $event->component->id,
            'action_by' => $event->component->updated_by,
        ]));
    }

    /**
     * Handle project deleting event.
     */
    public function handleBlueprintComponentDeleting(BlueprintComponentDeleting $event): void
    {
        Log::channel('project')->info(
            __('log.blueprint_component.deleting', [
                'project_blueprint_id' => $event->component->project_blueprint_id,
                'blueprint_component_id' => $event->component->id,
                'action_by' => auth()->id(),
            ])
        );
    }

    /**
     * Handle cache on project change event.
     */
    public function handleBlueprintComponentCache(BlueprintComponentCache $event): void
    {
        $this->repository
            ->initCacheKey()
            ->addCachePiece($event->component->id)
            ->removeCacheContent();
    }

    /**
     * Register the listeners for the subscriber.
     */
    public function subscribe(Dispatcher $events): void
    {
        $events->listen(
            BlueprintComponentCreated::class,
            [BlueprintComponentSubscriber::class, 'handleProjectBlueprintCreated']
        );

        $events->listen(
            BlueprintComponentUpdated::class,
            [BlueprintComponentSubscriber::class, 'handleBlueprintComponentUpdated']
        );

        $events->listen(
            BlueprintComponentActivated::class,
            [BlueprintComponentSubscriber::class, 'handleBlueprintComponentActivated']
        );

        $events->listen(
            BlueprintComponentDeleting::class,
            [BlueprintComponentSubscriber::class, 'handleBlueprintComponentDeleting']
        );

        $events->listen(
            BlueprintComponentCache::class,
            [BlueprintComponentSubscriber::class, 'handleBlueprintComponentCache']
        );
    }
}
