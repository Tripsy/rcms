<?php

namespace App\Actions;

use App\Actions\Traits\AsAction;
use App\Commands\ItemUpdateCommand;
use App\Repositories\Interfaces\ItemRepositoryInterface;

class ItemUpdate
{
    use AsAction;

    private ItemRepositoryInterface $itemRepository;

    public function __construct(ItemRepositoryInterface $itemRepository)
    {
        $this->itemRepository = $itemRepository;
    }

    public function handle(ItemUpdateCommand $command): void
    {
        try {
            $item = $this->itemRepository->findByUuid($this->command->getUuid());

            $this->itemRepository->update($item, [
                'description' => $this->command->getDescription(),
            ]);
        } catch (ModelNotFoundException) {
            $message = __('message.item.not_found', [
                'uuid' => $this->command->getUuid(),
            ]);

            throw new ActionException($message, Response::HTTP_NOT_FOUND); //this has to go away
        }
    }
}
