<?php

declare(strict_types=1);

namespace App\Commands;

use App\Commands\Traits\AttributesCommandTrait;
use App\Commands\Traits\GetIdCommandTrait;

class BlueprintComponentDeleteCommand
{
    use AttributesCommandTrait;
    use GetIdCommandTrait;

    private int $id;

    public function __construct(int $id)
    {
        $this->id = $id;
    }
}
