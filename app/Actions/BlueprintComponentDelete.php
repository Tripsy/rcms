<?php

namespace App\Actions;

use App\Actions\Traits\AsAction;
use App\Commands\BlueprintComponentDeleteCommand;
use App\Exceptions\ActionException;
use App\Queries\BlueprintComponentQuery;

class BlueprintComponentDelete
{
    use AsAction;

    private BlueprintComponentQuery $query;

    public function __construct(BlueprintComponentQuery $query)
    {
        $this->query = $query;
    }

    /**
     * @throws ActionException
     */
    public function handle(BlueprintComponentDeleteCommand $command): void
    {
        $this->query
            ->filterById($command->getId())
            ->filterByProjectBlueprintId($command->getProjectBlueprintId())
            ->deleteFirst();
    }
}
