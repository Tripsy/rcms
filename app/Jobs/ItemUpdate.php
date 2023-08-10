<?php

namespace App\Jobs;

use App\Commands\ItemUpdateCommand;
use App\Exceptions\JobException;
use App\Repositories\Interfaces\ItemRepositoryInterface;
use Exception;
use Illuminate\Bus\Queueable;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Symfony\Component\HttpFoundation\Response;

class ItemUpdate
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
     * @var ItemUpdateCommand
     */
    private ItemUpdateCommand $command;

    /**
     * Create a new job instance.
     */
    public function __construct(ItemUpdateCommand $command)
    {
        $this->command = $command;
    }

    /**
     * Execute the job.
     * @throws Exception
     */
    public function handle(ItemRepositoryInterface $itemRepository): void
    {
        try {
            $item = $itemRepository->findByUuid($this->command->getUuid());

            $itemRepository->update($item, [
                'description' => $this->command->getDescription(),
            ]);
        } catch (ModelNotFoundException) {
            $message = __('message.item.not_found', [
                'uuid' => $this->command->getUuid()
            ]);

            throw new JobException($message, Response::HTTP_NOT_FOUND);
        }
    }
}
