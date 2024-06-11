<?php

declare(strict_types=1);

namespace App\Commands;

use App\Commands\Traits\AttributesCommandTrait;
use App\Commands\Traits\GetIdCommandTrait;

class ItemDeleteCommand
{
    use AttributesCommandTrait;
    use GetIdCommandTrait;

    private int $id;

    private int $project_blueprint_id;

    public function __construct(int $id, int $project_blueprint_id)
    {
        $this->id = $id;
        $this->project_blueprint_id = $project_blueprint_id;
    }

    public function getProjectBlueprintId(): int
    {
        return $this->project_blueprint_id;
    }
}
