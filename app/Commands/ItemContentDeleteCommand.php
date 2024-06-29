<?php

declare(strict_types=1);

namespace App\Commands;

use App\Commands\Traits\AttributesCommandTrait;
use App\Commands\Traits\GetIdCommandTrait;

class ItemContentDeleteCommand
{
    use AttributesCommandTrait;
    use GetIdCommandTrait;

    private int $id;

    private int $item_id;

    public function __construct(int $id, $item_id)
    {
        $this->id = $id;
        $this->item_id = $item_id;
    }

    public function getItemId(): int
    {
        return $this->item_id;
    }
}
