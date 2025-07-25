<?php

declare(strict_types=1);

namespace App\Actions;

use App\Actions\Traits\AsAction;
use App\Commands\BlueprintComponentStatusUpdateCommand;
use App\Exceptions\ActionException;
use App\Queries\BlueprintComponentQuery;

class BlueprintComponentStatusUpdate
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
    public function handle(BlueprintComponentStatusUpdateCommand $command): void
    {
        $this->query
            ->filterById($command->getId())
            ->updateFirst([
                'status' => $command->getStatus(),
            ]);
    }
}
