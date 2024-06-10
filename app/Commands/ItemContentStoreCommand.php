<?php

declare(strict_types=1);

namespace App\Commands;

use App\Commands\Traits\AttributesCommandTrait;

class ItemContentStoreCommand
{
    use AttributesCommandTrait;

    private int $item_id;

    private int $blueprint_component_id;

    private string $content;

    public function __construct(int $item_id, int $blueprint_component_id, string $content)
    {
        $this->item_id = $item_id;
        $this->blueprint_component_id = $blueprint_component_id;
        $this->content = $content;
    }

    public function getItemId(): int
    {
        return $this->item_id;
    }

    public function getBlueprintComponentId(): int
    {
        return $this->blueprint_component_id;
    }

    public function getContent(): string
    {
        return $this->content;
    }
}
