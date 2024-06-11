<?php

declare(strict_types=1);

namespace App\Commands;

use App\Commands\Traits\AttributesCommandTrait;
use App\Commands\Traits\GetIdCommandTrait;
use App\Commands\Traits\GetProjectIdCommandTrait;

class ProjectPermissionDeleteCommand
{
    use AttributesCommandTrait;
    use GetIdCommandTrait;
    use GetProjectIdCommandTrait;

    private int $id;

    private int $project_id;

    public function __construct(int $id, int $project_id)
    {
        $this->id = $id;
        $this->project_id = $project_id;
    }
}
