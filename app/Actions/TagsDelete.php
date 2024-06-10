<?php

declare(strict_types=1);

namespace App\Actions;

use App\Actions\Traits\AsAction;
use App\Commands\TagsDeleteCommand;
use App\Exceptions\ActionException;
use App\Queries\TagsQuery;

class TagsDelete
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
    public function handle(TagsDeleteCommand $command): void
    {
        $this->query
            ->filterById($command->getId())
            ->deleteFirst();
    }
}
