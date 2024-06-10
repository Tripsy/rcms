<?php

declare(strict_types=1);

namespace App\Actions;

use App\Actions\Traits\AsAction;
use App\Commands\ItemStatusUpdateCommand;
use App\Exceptions\ActionException;
use App\Queries\ItemQuery;

class ItemStatusUpdate
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
    public function handle(ItemStatusUpdateCommand $command): void
    {
        $this->query
            ->filterById($command->getId())
            ->updateFirst([
                'status' => $command->getStatus(),
            ]);
    }
}
