<?php

namespace App\Actions;

use App\Actions\Traits\AsAction;
use App\Commands\BlueprintComponentDeleteCommand;
use App\Exceptions\ActionException;
use App\Queries\BlueprintComponentDeleteQuery;

class BlueprintComponentDelete
{
    use AsAction;

    private BlueprintComponentDeleteQuery $query;

    public function __construct(BlueprintComponentDeleteQuery $query)
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
            ->deleteFirst();
    }
}
