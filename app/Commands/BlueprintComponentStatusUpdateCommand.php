<?php

declare(strict_types=1);

namespace App\Commands;

use App\Commands\Traits\AttributesCommandTrait;
use App\Commands\Traits\GetIdCommandTrait;
use App\Commands\Traits\GetStatusCommandTrait;
use App\Enums\CommonStatus;

class BlueprintComponentStatusUpdateCommand
{
    use AttributesCommandTrait;
    use GetIdCommandTrait;
    use GetStatusCommandTrait;

    private int $id;

    private int $project_blueprint_id;

    private CommonStatus $status;

    public function __construct(int $id, int $project_blueprint_id, string $status)
    {
        $this->id = $id;
        $this->project_blueprint_id = $project_blueprint_id;
        $this->status = CommonStatus::tryFrom($status) ?? CommonStatus::ACTIVE;
    }

    /**
     * Return attribute `project_id`
     */
    public function getProjectBlueprintId(): int
    {
        return $this->project_blueprint_id;
    }
}
