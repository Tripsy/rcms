<?php

declare(strict_types=1);

namespace App\Commands;

class ItemDataStoreCommand
{
    private string $uuid;
    private string $label;
    private string $content;

    public function __construct(string $uuid, string $label, string $content)
    {
        $this->uuid = $uuid;
        $this->label = $label;
        $this->content = $content;
    }

    public function getUuid(): string
    {
        return $this->uuid;
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
