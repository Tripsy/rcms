<?php

declare(strict_types=1);

namespace App\Commands;

use App\Commands\Traits\AttributesCommandTrait;
use App\Commands\Traits\GetDescriptionCommandTrait;
use App\Commands\Traits\GetIdCommandTrait;
use App\Commands\Traits\GetNameCommandTrait;
use App\Commands\Traits\GetProjectIdCommandTrait;

class ProjectBlueprintUpdateCommand
{
    use AttributesCommandTrait;
    use GetDescriptionCommandTrait;
    use GetIdCommandTrait;
    use GetProjectIdCommandTrait;
    use GetNameCommandTrait;

    private int $id;

    private int $project_id;

    private string $name;

    private ?string $description;

    public function __construct(int $id, int $project_id, string $name, ?string $description)
    {
        $this->id = $id;
        $this->project_id = $project_id;
        $this->name = $name;
        $this->description = $description;
    }
}
