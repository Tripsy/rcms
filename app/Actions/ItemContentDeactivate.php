<?php

declare(strict_types=1);

namespace App\Actions;

use App\Actions\Traits\AsAction;
use App\Commands\ItemContentDeactivateCommand;
use App\Exceptions\ActionException;
use App\Queries\ItemContentQuery;

class ItemContentDeactivate
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
    public function handle(ItemContentDeactivateCommand $command): void
    {
        $this->query
            ->filterByItemId($command->getItemId())
            ->filterByBlueprintComponentId($command->getBlueprintComponentId())
            ->isActive()
            ->updateIfExist([
                'is_active' => $command->getIsActive(),
            ]);
    }
}
