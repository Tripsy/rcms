<?php

declare(strict_types=1);

namespace App\Commands;

use App\Commands\Traits\AttributesCommandTrait;
use App\Commands\Traits\GetUuidCommandTrait;

class ItemDataStoreCommand
{
    use AttributesCommandTrait;
    use GetUuidCommandTrait;

    private string $uuid;
    private string $label;
    private string $content;

    public function __construct(string $uuid, string $label, string $content)
    {
        $this->uuid = $uuid;
        $this->label = $label;
        $this->content = $content;
    }

    public function getLabel(): string
    {
        return $this->label;
    }

    public function getContent(): string
    {
        return $this->content;
    }
}
