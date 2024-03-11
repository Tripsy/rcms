<?php

declare(strict_types=1);

namespace App\Actions;

use App\Actions\Traits\AsAction;
use App\Commands\BlueprintComponentUpdateCommand;
use App\Queries\BlueprintComponentUpdateQuery;

class BlueprintComponentUpdate
{
    use AsAction;

    private BlueprintComponentUpdateQuery $query;

    public function __construct(BlueprintComponentUpdateQuery $query)
    {
        $this->query = $query;
    }

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
