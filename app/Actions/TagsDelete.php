<?php

declare(strict_types=1);

namespace App\Actions;

use App\Actions\Traits\AsAction;
use App\Commands\TagsDeleteCommand;
use App\Exceptions\ActionException;
use App\Queries\TagQuery;

class TagsDelete
{
    use AsAction;

    private TagQuery $query;

    public function __construct(TagQuery $query)
    {
        $this->query = $query;
    }

    /**
     * @throws ActionException
     */
    public function handle(TagsDeleteCommand $command): void
    {
        $this->query
            ->filterById($command->getId())
            ->filterByProjectId($command->getProjectId())
            ->deleteFirst();
    }
}
