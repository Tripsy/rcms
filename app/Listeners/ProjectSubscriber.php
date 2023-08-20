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
            'id_project' => $event->project->id,
            'name' => $event->project->name
        ]));

        //TODO cache
//        Log::channel('test')->info(__('log.cache.projects', [
//            'id_project' => $event->project->id
//        ]));
    }

    /**
     * Handle project updated event.
     */
    public function handleProjectUpdated(ProjectUpdated $event): void
    {
        Log::channel('project')->info(
            __('log.project.updated', [
                'id_project' => $event->project->id
            ]),
            $event->project->getFillableChanges()
        );

        if ($event->project->wasChanged('status') && $event->project->status == CommonStatus::ACTIVE) {
            ProjectActivated::dispatch($event->project);
        }

        //TODO cache
//        Log::channel('test')->info(__('log.cache.projects', [
//            'id_project' => $event->project->id
//        ]));
    }

    /**
     * Handle project activated event.
     */
    public function handleProjectActivated(ProjectActivated $event): void
    {
        Log::channel('project')->info(__('log.project.activated', [
            'id_project' => $event->project->id,
            'id_user' => $event->project->updated_by,
        ]));
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
