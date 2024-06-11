<?php

declare(strict_types=1);

namespace App\Commands;

use App\Commands\Traits\AttributesCommandTrait;
use App\Commands\Traits\GetIdCommandTrait;
use App\Commands\Traits\GetProjectIdCommandTrait;
use App\Enums\ProjectPermissionRole;

class ProjectPermissionUpdateCommand
{
    use AttributesCommandTrait;
    use GetIdCommandTrait;
    use GetProjectIdCommandTrait;

    private int $id;

    private int $project_id;

    private ProjectPermissionRole $role;

    public function __construct(int $id, int $project_id, string $role)
    {
        $this->id = $id;
        $this->project_id = $project_id;
        $this->role = ProjectPermissionRole::tryFrom($role) ?? ProjectPermissionRole::OPERATOR;
    }

    public function getRole(): ProjectPermissionRole
    {
        return $this->role;
    }
}
