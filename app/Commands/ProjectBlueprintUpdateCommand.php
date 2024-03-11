<?php

declare(strict_types=1);

namespace App\Commands;

use App\Commands\Traits\AttributesCommandTrait;
use App\Commands\Traits\GetDescriptionCommandTrait;
use App\Commands\Traits\GetIdCommandTrait;
use App\Commands\Traits\GetNameCommandTrait;

class ProjectBlueprintUpdateCommand
{
    use AttributesCommandTrait;
    use GetDescriptionCommandTrait;
    use GetIdCommandTrait;
    use GetNameCommandTrait;

    private int $id;

    private string $name;

    private ?string $description;

    public function __construct(int $id, string $name, ?string $description)
    {
        $this->id = $id;
        $this->name = $name;
        $this->description = $description;
    }
}
