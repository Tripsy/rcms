<?php

declare(strict_types=1);

namespace App\Commands;

use App\Commands\Traits\AttributesCommandTrait;
use App\Commands\Traits\GetIdCommandTrait;
use App\Commands\Traits\GetStatusCommandTrait;
use App\Enums\ItemStatus;

class ItemStatusUpdateCommand
{
    use AttributesCommandTrait;
    use GetIdCommandTrait;
    use GetStatusCommandTrait;

    private int $id;

    private ItemStatus $status;

    public function __construct(int $id, string $status)
    {
        $this->id = $id;
        $this->status = ItemStatus::tryFrom($status) ?? ItemStatus::ACTIVE;
    }
}
