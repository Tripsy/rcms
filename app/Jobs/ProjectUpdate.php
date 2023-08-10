<?php

namespace App\Jobs;

use App\Commands\ProjectUpdateCommand;
use App\Models\Project;
use App\Repositories\Interfaces\ProjectRepositoryInterface;
use Exception;
use Illuminate\Bus\Queueable;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class ProjectUpdate
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * The number of seconds to wait before retrying the job.
     *
     * @var int
     */
    public int $backoff = 60;

    /**
     * The number of times the job may be attempted.
     *
     * @var int
     */
    public int $tries = 1;

    /**
     * The number of seconds the job can run before timing out.
     *
     * @var int
     */
    public int $timeout = 120;


    private ProjectUpdateCommand $command;
    private Project $model;

    /**
     * Create a new job instance.
     */
    public function __construct(ProjectUpdateCommand $command, Project $model)
    {
        $this->command = $command;
        $this->model = $model;
    }

    /**
     * Execute the job.
     * @throws Exception
     */
    public function handle(ProjectRepositoryInterface $projectRepository): void
    {
        $projectRepository->update($this->model, [
            'name' => $this->command->getName(),
            'authority_name' => $this->command->getAuthorityName(),
        ]);
    }
}
