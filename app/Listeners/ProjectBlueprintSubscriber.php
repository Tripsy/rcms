<?php

declare(strict_types=1);

namespace App\Listeners;

use App\Enums\CommonStatus;
use App\Events\ProjectBlueprintActivated;
use App\Events\ProjectBlueprintCache;
use App\Events\ProjectBlueprintCreated;
use App\Events\ProjectBlueprintDeleting;
use App\Events\ProjectBlueprintUpdated;
use App\Repositories\ProjectBlueprintRepository;
use Illuminate\Events\Dispatcher;
use Illuminate\Support\Facades\Log;

class ProjectBlueprintSubscriber
{
    private ProjectBlueprintRepository $repository;

    public function __construct(ProjectBlueprintRepository $repository)
    {
        $this->repository = $repository;
    }

    /**
     * Handle project created event.
     */
    public function handleProjectBlueprintCreated(ProjectBlueprintCreated $event): void
    {
        Log::channel('project')->info(__('log.project_blueprint.created', [
            'project_id' => $event->blueprint->project_id,
            'project_blueprint_id' => $event->blueprint->id,
            'action_by' => $event->blueprint->created_by,
        ]));
    }

    /**
     * Handle project updated event.
     */
    public function handleProjectBlueprintUpdated(ProjectBlueprintUpdated $event): void
    {
        Log::channel('project')->info(
            __('log.project_blueprint.updated', [
                'project_id' => $event->blueprint->project_id,
                'project_blueprint_id' => $event->blueprint->id,
                'action_by' => $event->blueprint->updated_by,
            ]),
            $event->blueprint->getFillableChanges()
        );

        if ($event->blueprint->wasChanged('status') && $event->blueprint->status == CommonStatus::ACTIVE) {
            ProjectBlueprintActivated::dispatch($event->blueprint);
        }
    }

    /**
     * Handle project activated event.
     */
    public function handleProjectBlueprintActivated(ProjectBlueprintActivated $event): void
    {
        Log::channel('project')->info(__('log.project_blueprint.activated', [
            'project_id' => $event->blueprint->project_id,
            'project_blueprint_id' => $event->blueprint->id,
            'action_by' => $event->blueprint->updated_by,
        ]));
    }

    /**
     * Handle project deleting event.
     */
    public function handleProjectBlueprintDeleting(ProjectBlueprintDeleting $event): void
    {
        Log::channel('project')->info(
            __('log.project_blueprint.deleting', [
                'project_id' => $event->blueprint->project_id,
                'project_blueprint_id' => $event->blueprint->id,
                'action_by' => auth()->id(),
            ])
        );
    }

    /**
     * Handle cache on project change event.
     */
    public function handleProjectBlueprintCache(ProjectBlueprintCache $event): void
    {
        $this->repository
            ->initCacheKey()
            ->addCachePiece($event->blueprint->id)
            ->removeCacheContent();
    }

    /**
     * Register the listeners for the subscriber.
     */
    public function subscribe(Dispatcher $events): void
    {
        $events->listen(
            ProjectBlueprintCreated::class,
            [ProjectBlueprintSubscriber::class, 'handleProjectBlueprintCreated']
        );

        $events->listen(
            ProjectBlueprintUpdated::class,
            [ProjectBlueprintSubscriber::class, 'handleProjectBlueprintUpdated']
        );

        $events->listen(
            ProjectBlueprintActivated::class,
            [ProjectBlueprintSubscriber::class, 'handleProjectBlueprintActivated']
        );

        $events->listen(
            ProjectBlueprintDeleting::class,
            [ProjectBlueprintSubscriber::class, 'handleProjectBlueprintDeleting']
        );

        $events->listen(
            ProjectBlueprintCache::class,
            [ProjectBlueprintSubscriber::class, 'handleProjectBlueprintCache']
        );
    }
}
