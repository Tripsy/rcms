<?php

declare(strict_types=1);

namespace App\Commands;

class ItemUpdateCommand
{
    private string $uuid;
    private string $description;

    public function __construct(string $uuid, string $description)
    {
        $this->uuid = $uuid;
        $this->description = $description;
    }

    public function getUuid(): string
    {
        return $this->uuid;
    }

    public function getDescription(): string
    {
        return $this->description;
    }
}
