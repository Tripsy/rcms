<?php

declare(strict_types=1);

namespace App\Actions;

use App\Actions\Traits\AsAction;
use App\Commands\ItemDeleteCommand;
use App\Exceptions\ActionException;
use App\Queries\ItemQuery;

class ItemDelete
{
    use AsAction;

    private ItemQuery $query;

    public function __construct(ItemQuery $query)
    {
        $this->query = $query;
    }

    /**
     * @throws ActionException
     */
    public function handle(ItemDeleteCommand $command): void
    {
        $this->query
            ->filterById($command->getId())
            ->deleteFirst();
    }
}
