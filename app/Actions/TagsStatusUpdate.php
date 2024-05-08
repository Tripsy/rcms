<?php

declare(strict_types=1);

namespace App\Actions;

use App\Actions\Traits\AsAction;
use App\Commands\TagsStatusUpdateCommand;
use App\Exceptions\ActionException;
use App\Queries\TagsUpdateQuery;

class TagsStatusUpdate
{
    use AsAction;

    private TagsUpdateQuery $query;

    public function __construct(TagsUpdateQuery $query)
    {
        $this->query = $query;
    }

    /**
     * @throws ActionException
     */
    public function handle(TagsStatusUpdateCommand $command): void
    {
        $this->query
            ->filterById($command->getId())
            ->updateFirst([
                'status' => $command->getStatus(),
            ]);
    }
}
