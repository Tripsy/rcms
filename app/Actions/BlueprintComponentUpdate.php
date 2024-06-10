<?php

declare(strict_types=1);

namespace App\Actions;

use App\Actions\Traits\AsAction;
use App\Commands\BlueprintComponentUpdateCommand;
use App\Exceptions\ActionException;
use App\Queries\BlueprintComponentQuery;

class BlueprintComponentUpdate
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
    public function handle(BlueprintComponentUpdateCommand $command): void
    {
        $this->query
            ->filterById($command->getId())
            ->updateFirst([
                'name' => $command->getName(),
                'description' => $command->getDescription(),
                'info' => $command->getInfo(),
                'component_type' => $command->getComponentType(),
                'component_format' => $command->getComponentFormat(),
                'type_options' => $command->getTypeOptions(),
                'is_required' => $command->getIsRequired(),
            ]);
    }
}
