<?php

declare(strict_types=1);

namespace App\Actions;

use App\Actions\Traits\AsAction;
use App\Commands\TagsStatusUpdateCommand;
use App\Exceptions\ActionException;
use App\Queries\TagsQuery;

class TagsStatusUpdate
{
    use AsAction;

    private TagsQuery $query;

    public function __construct(TagsQuery $query)
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
            ->filterByProjectId($command->getProjectId())
            ->updateFirst([
                'status' => $command->getStatus(),
            ]);
    }
}
