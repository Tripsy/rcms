<?php

declare(strict_types=1);

namespace App\Commands;

use App\Commands\Traits\AttributesCommandTrait;
use App\Commands\Traits\GetDescriptionCommandTrait;
use App\Commands\Traits\GetIdCommandTrait;

class ItemUpdateCommand
{
    use AttributesCommandTrait;
    use GetIdCommandTrait;
    use GetDescriptionCommandTrait;

    private int $id;

    private string $description;

    public function __construct(int $id, string $description)
    {
        $this->id = $id;
        $this->description = $description;
    }
}
