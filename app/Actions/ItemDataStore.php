<?php

namespace App\Actions;

use App\Actions\Traits\AsAction;
use App\Commands\ItemDataStoreCommand;
use App\Exceptions\ActionException;
use App\Repositories\Interfaces\ItemDataRepositoryInterface;
use App\Repositories\Interfaces\ItemRepositoryInterface;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Symfony\Component\HttpFoundation\Response;

class ItemDataStore
{
    use AsAction;

    private ItemRepositoryInterface $itemRepository;
    private ItemDataRepositoryInterface $itemDataRepository;

    public function __construct(ItemRepositoryInterface $itemRepository, ItemDataRepositoryInterface $itemDataRepository)
    {
        $this->itemRepository = $itemRepository;
        $this->itemDataRepository = $itemDataRepository;
    }

    public function handle(ItemDataStoreCommand $command): void
    {
        try {
            $this->itemRepository->findByUuid($command->getUuid());

            $this->itemDataRepository->create([
                'uuid' => $command->getUuid(),
                'label' => $command->getLabel(),
                'content' => $command->getContent(),
            ]);
        } catch (ModelNotFoundException) {
            $message = __('message.item.cannot_create_data', [
                'uuid' => $command->getUuid()
            ]);

            throw new ActionException($message, Response::HTTP_UNPROCESSABLE_ENTITY); this has to go away
        }
    }
}
