<?php

declare(strict_types=1);

namespace App\Actions;

use App\Actions\Traits\AsAction;
use App\Commands\TagsUpdateCommand;
use App\Exceptions\ActionException;
use App\Queries\TagQuery;

class TagsUpdate
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
    public function handle(TagsUpdateCommand $command): void
    {
        $updateData = [
            'name' => $command->getName(),
        ];

        if (is_null($command->getDescription()) === false) {
            $updateData['description'] = $command->getDescription();
        }

        if (is_null($command->getIsCategory()) === false) {
            $updateData['is_category'] = $command->getIsCategory();
        }

        $this->query
            ->filterById($command->getId())
            ->filterByProjectId($command->getProjectId())
            ->updateFirst($updateData);
    }
}
