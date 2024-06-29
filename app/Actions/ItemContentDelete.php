<?php

declare(strict_types=1);

namespace App\Actions;

use App\Actions\Traits\AsAction;
use App\Commands\ItemContentDeleteCommand;
use App\Exceptions\ActionException;
use App\Queries\ItemContentQuery;

class ItemContentDelete
{
    use AsAction;

    private ItemContentQuery $query;

    public function __construct(ItemContentQuery $query)
    {
        $this->query = $query;
    }

    /**
     * @throws ActionException
     */
    public function handle(ItemContentDeleteCommand $command): void
    {
        $this->query
            ->filterById($command->getId())
            ->filterByItemId($command->getItemId())
            ->deleteFirst();
    }
}
