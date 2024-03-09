<?php

declare(strict_types=1);

namespace App\Listeners;

use App\Enums\CommonStatus;
use App\Events\ProjectPermissionActivated;
use App\Events\ProjectPermissionCache;
use App\Events\ProjectPermissionCreated;
use App\Events\ProjectPermissionDeleting;
use App\Events\ProjectPermissionUpdated;
use App\Repositories\ProjectPermissionRepository;
use Illuminate\Events\Dispatcher;
use Illuminate\Support\Facades\Log;

class ProjectPermissionSubscriber
{
    private ProjectPermissionRepository $repository;

    public function __construct(ProjectPermissionRepository $repository)
    {
        $this->repository = $repository;
    }

    /**
     * Handle project permission created event.
     */
    public function handleProjectPermissionCreated(ProjectPermissionCreated $event): void
    {
        Log::channel('project')->info(__('log.project_permission.created', [
            'project_id' => $event->permission->project_id,
            'project_permission_id' => $event->permission->id,
            'user_id' => $event->permission->user_id,
            'action_by' => $event->permission->created_by,
        ]));
    }

    /**
     * Handle project permission updated event.
     */
    public function handleProjectPermissionUpdated(ProjectPermissionUpdated $event): void
    {
        Log::channel('project')->info(
            __('log.project_permission.updated', [
                'project_id' => $event->permission->project_id,
                'project_permission_id' => $event->permission->id,
                'action_by' => $event->permission->updated_by,
            ]),
            $event->permission->getFillableChanges()
        );

        if ($event->permission->wasChanged('status') && $event->permission->status == CommonStatus::ACTIVE) {
            ProjectPermissionActivated::dispatch($event->permission);
        }
    }

    /**
     * Handle project activated event.
     */
    public function handleProjectPermissionActivated(ProjectPermissionActivated $event): void
    {
        Log::channel('project')->info(__('log.project_permission.activated', [
            'project_id' => $event->permission->project_id,
            'project_permission_id' => $event->permission->id,
            'action_by' => $event->permission->updated_by,
        ]));
    }

    /**
     * Handle project deleting event.
     */
    public function handleProjectDeleting(ProjectPermissionDeleting $event): void
    {
        Log::channel('project')->info(
            __('log.project_permission.deleting', [
                'project_id' => $event->permission->project_id,
                'project_permission_id' => $event->permission->id,
                'action_by' => auth()->id(),
            ])
        );
    }

    /**
     * Handle cache on project permission change event.
     */
    public function handleProjectPermissionCache(ProjectPermissionCache $event): void
    {
        $this->repository
            ->initCacheKey()
            ->addCachePiece($event->permission->id)
            ->removeCacheContent();
    }

    /**
     * Register the listeners for the subscriber.
     */
    public function subscribe(Dispatcher $events): void
    {
        $events->listen(
            ProjectPermissionCreated::class,
            [ProjectPermissionSubscriber::class, 'handleProjectPermissionCreated']
        );

        $events->listen(
            ProjectPermissionUpdated::class,
            [ProjectPermissionSubscriber::class, 'handleProjectPermissionUpdated']
        );

        $events->listen(
            ProjectPermissionActivated::class,
            [ProjectPermissionSubscriber::class, 'handleProjectPermissionActivated']
        );

        $events->listen(
            ProjectPermissionDeleting::class,
            [ProjectPermissionSubscriber::class, 'handleProjectDeleting']
        );

        $events->listen(
            ProjectPermissionCache::class,
            [ProjectPermissionSubscriber::class, 'handleProjectPermissionCache']
        );
    }
}
