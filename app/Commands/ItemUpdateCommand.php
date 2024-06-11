<?php

declare(strict_types=1);

namespace App\Commands;

use App\Commands\Traits\AttributesCommandTrait;
use App\Commands\Traits\GetDescriptionCommandTrait;
use App\Commands\Traits\GetIdCommandTrait;

class ItemUpdateCommand
{
    use AttributesCommandTrait;
    use GetDescriptionCommandTrait;
    use GetIdCommandTrait;

    private int $id;

    private int $project_blueprint_id;

    private string $description;

    public function __construct(int $id, int $project_blueprint_id, string $description)
    {
        $this->id = $id;
        $this->project_blueprint_id = $project_blueprint_id;
        $this->description = $description;
    }

    public function getProjectBlueprintId(): int
    {
        return $this->project_blueprint_id;
    }
}
