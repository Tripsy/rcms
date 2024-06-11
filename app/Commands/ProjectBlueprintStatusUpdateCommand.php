<?php

declare(strict_types=1);

namespace App\Commands;

use App\Commands\Traits\AttributesCommandTrait;
use App\Commands\Traits\GetIdCommandTrait;
use App\Commands\Traits\GetProjectIdCommandTrait;
use App\Commands\Traits\GetStatusCommandTrait;
use App\Enums\CommonStatus;

class ProjectBlueprintStatusUpdateCommand
{
    use AttributesCommandTrait;
    use GetIdCommandTrait;
    use GetProjectIdCommandTrait;
    use GetStatusCommandTrait;

    private int $id;

    private int $project_id;

    private CommonStatus $status;

    public function __construct(int $id, int $project_id, string $status)
    {
        $this->id = $id;
        $this->project_id = $project_id;
        $this->status = CommonStatus::tryFrom($status) ?? CommonStatus::ACTIVE;
    }
}
