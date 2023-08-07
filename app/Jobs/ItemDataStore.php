<?php

namespace App\Jobs;

use App\Commands\ItemDataStoreCommand;
use App\Exceptions\JobException;
use App\Interfaces\ItemDataRepositoryInterface;
use App\Interfaces\ItemRepositoryInterface;
use Exception;
use Illuminate\Bus\Queueable;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Symfony\Component\HttpFoundation\Response;

class ItemDataStore
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    private ItemDataStoreCommand $command;

    /**
     * Create a new job instance.
     */
    public function __construct(ItemDataStoreCommand $command)
    {
        $this->command = $command;
    }

    /**
     * Execute the job.
     *
     * @throws Exception
     */
    public function handle(ItemRepositoryInterface $itemRepository, ItemDataRepositoryInterface $itemDataRepository): void
    {
        try {
            $itemRepository->findByUuid($this->command->getUuid());

            $itemDataRepository->create([
                'uuid' => $this->command->getUuid(),
                'label' => $this->command->getLabel(),
                'content' => $this->command->getContent(),
            ]);
        } catch (ModelNotFoundException) {
            $message = __('message.item.cannot_create_data', [
                'uuid' => $this->command->getUuid()
            ]);

            throw new JobException($message, Response::HTTP_UNPROCESSABLE_ENTITY);
        }
    }
}
