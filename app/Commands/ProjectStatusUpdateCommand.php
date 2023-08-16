<?php

declare(strict_types=1);

namespace App\Commands;

use App\Commands\Traits\AttributesCommandTrait;
use App\Commands\Traits\GetIdCommandTrait;
use App\Enums\CommonStatus;

class ProjectStatusUpdateCommand
{
    use AttributesCommandTrait;
    use GetIdCommandTrait;

    private int $id;
    private CommonStatus $status;

    public function __construct(int $id, string $status)
    {
        $this->id = $id;
        $this->status = CommonStatus::tryFrom($status) ?? CommonStatus::ACTIVE;
    }
}
