<?php

declare(strict_types=1);

namespace App\Actions;

use App\Actions\Traits\AsAction;
use App\Commands\ItemContentStatusUpdateCommand;
use App\Exceptions\ActionException;
use App\Queries\ItemContentQuery;

class ItemContentStatusUpdate
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
    public function handle(ItemContentStatusUpdateCommand $command): void
    {
        $this->query
            ->filterById($command->getId())
            ->updateFirst([
                'status' => $command->getStatus(),
            ]);
    }
}
