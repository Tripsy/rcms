<?php

declare(strict_types=1);

namespace App\Commands;

use App\Commands\Traits\AttributesCommandTrait;
use App\Enums\DefaultOption;
use BackedEnum;

class ItemContentDeactivateCommand
{
    use AttributesCommandTrait;

    private int $item_id;

    private int $blueprint_component_id;

    private DefaultOption $is_active;

    public function __construct(int $item_id, int $blueprint_component_id)
    {
        $this->item_id = $item_id;
        $this->blueprint_component_id = $blueprint_component_id;
        $this->is_active = DefaultOption::NO;
    }

    public function getItemId(): int
    {
        return $this->item_id;
    }

    public function getBlueprintComponentId(): int
    {
        return $this->blueprint_component_id;
    }

    public function getIsActive(): BackedEnum
    {
        return $this->is_active;
    }
}
