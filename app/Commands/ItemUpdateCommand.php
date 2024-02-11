<?php

declare(strict_types=1);

namespace App\Commands;

use App\Commands\Traits\AttributesCommandTrait;
use App\Commands\Traits\GetUuidCommandTrait;

class ItemUpdateCommand
{
    use AttributesCommandTrait;
    use GetUuidCommandTrait;

    private string $uuid;

    private string $description;

    public function __construct(string $uuid, string $description)
    {
        $this->uuid = $uuid;
        $this->description = $description;
    }

    public function getDescription(): string
    {
        return $this->description;
    }
}
