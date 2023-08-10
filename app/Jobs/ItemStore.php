<?php

namespace App\Jobs;

use App\Commands\ItemStoreCommand;
use App\Repositories\Interfaces\ItemRepositoryInterface;
use Exception;
use Illuminate\Bus\Queueable;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class ItemStore
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

    /**
     * @var ItemStoreCommand
     */
    private ItemStoreCommand $command;

    /**
     * Create a new job instance.
     */
    public function __construct(ItemStoreCommand $command)
    {
        $this->command = $command;
    }

    /**
     * Execute the job.
     * @throws Exception
     */
    public function handle(ItemRepositoryInterface $itemRepository): void
    {
        $itemRepository->create([
            'uuid' => $this->command->getUuid(),
            'project_id' => $this->command->getProjectId(),
            'description' => $this->command->getDescription(),
            'status' => $this->command->getStatus(),
        ]);
    }
}
