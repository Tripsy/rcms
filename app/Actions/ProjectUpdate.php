<?php

namespace App\Actions;

use App\Actions\Traits\AsAction;
use App\Commands\ProjectStoreCommand;
use App\Repositories\Interfaces\ProjectRepositoryInterface;

class ProjectUpdate
{
    use AsAction;

    private ProjectRepositoryInterface $projectRepository;

    public function __construct(ProjectRepositoryInterface $projectRepository)
    {
        $this->projectRepository = $projectRepository;
    }

    public function handle(ProjectStoreCommand $command): void
    {
        $this->projectRepository->create([
            'name' => $command->getName(),
            'authority_name' => $command->getAuthorityName(),
        ]);
    }
}
