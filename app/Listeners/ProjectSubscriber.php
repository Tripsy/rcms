<?php

declare(strict_types=1);

namespace App\Listeners;

use App\Enums\CommonStatus;
use App\Events\ProjectActivated;
use App\Events\ProjectCache;
use App\Events\ProjectCreated;
use App\Events\ProjectUpdated;
use App\Repositories\ProjectRepository;
use Illuminate\Events\Dispatcher;
use Illuminate\Support\Facades\Log;

class ProjectSubscriber
{
    private ProjectRepository $repository;

    public function __construct(ProjectRepository $repository)
    {
        $this->repository = $repository;
    }

    /**
     * Handle project created event.
     */
    public function handleProjectCreated(ProjectCreated $event): void
    {
        Log::channel('project')->info(__('log.project.created', [
                'project_id' => $event->project->id,
                'name' => $event->project->name,
                'created_by' => $event->project->created_by,
            ])
        );
    }

    /**
     * Handle project updated event.
     */
    public function handleProjectUpdated(ProjectUpdated $event): void
    {
        Log::channel('project')->info(__('log.project.updated', [
                'project_id' => $event->project->id,
                'updated_by' => $event->project->updated_by,
            ]),
            $event->project->getFillableChanges()
        );

        if ($event->project->wasChanged('status') && $event->project->status == CommonStatus::ACTIVE) {
            ProjectActivated::dispatch($event->project);
        }
    }

    /**
     * Handle project activated event.
     */
    public function handleProjectActivated(ProjectActivated $event): void
    {
        Log::channel('project')->info(__('log.project.activated', [
                'project_id' => $event->project->id,
                'updated_by' => $event->project->updated_by,
            ])
        );
    }

    /**
     * Handle cache on project change event.
     */
    public function handleProjectCache(ProjectCache $event): void
    {
         $this->repository
            ->buildCacheTags(['list'])
            ->flushCacheByTags();

         if (empty($event->project->id) === false) {
             $this->repository
                 ->buildCacheTags(['list'])
                 ->buildCacheKey($event->project->id)
                 ->removeCacheContent();
         }
    }

    /**
     * Register the listeners for the subscriber.
     */
    public function subscribe(Dispatcher $events): void
    {
        $events->listen(
            ProjectCreated::class,
            [ProjectSubscriber::class, 'handleProjectCreated']
        );

        $events->listen(
            ProjectUpdated::class,
            [ProjectSubscriber::class, 'handleProjectUpdated']
        );

        $events->listen(
            ProjectActivated::class,
            [ProjectSubscriber::class, 'handleProjectActivated']
        );

        $events->listen(
            ProjectCache::class,
            [ProjectSubscriber::class, 'handleProjectCache']
        );
    }
}
