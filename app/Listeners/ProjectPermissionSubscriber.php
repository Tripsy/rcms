<?php

declare(strict_types=1);

namespace App\Listeners;

use App\Enums\CommonStatus;
use App\Events\ProjectPermissionActivated;
use App\Events\ProjectPermissionCache;
use App\Events\ProjectPermissionCreated;
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
                'project_permission_id' => $event->permission->id,
                'created_by' => $event->permission->created_by,
            ]));
    }

    /**
     * Handle project permission updated event.
     */
    public function handleProjectPermissionUpdated(ProjectPermissionUpdated $event): void
    {
        Log::channel('project')->info(
            __('log.project_permission.updated', [
                'project_permission_id' => $event->permission->id,
                'updated_by' => $event->permission->updated_by,
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
                'project_permission_id' => $event->permission->id,
                'updated_by' => $event->permission->updated_by,
            ]));
    }

    /**
     * Handle cache on project permission change event.
     */
    public function handleProjectPermissionCache(ProjectPermissionCache $event): void
    {
        $this->repository
           ->buildCacheTags(['list'])
           ->flushCacheByTags();

        if (empty($event->project->id) === false) {
            $this->repository
                ->buildCacheTags(['list'])
                ->buildCacheKey($event->permission->id)
                ->removeCacheContent();
        }
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
            ProjectPermissionCache::class,
            [ProjectPermissionSubscriber::class, 'handleProjectPermissionCache']
        );
    }
}
