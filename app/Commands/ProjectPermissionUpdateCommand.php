<?php

declare(strict_types=1);

namespace App\Commands;

use App\Commands\Traits\AttributesCommandTrait;
use App\Commands\Traits\GetIdCommandTrait;
use App\Enums\ProjectPermissionRole;

class ProjectPermissionUpdateCommand
{
    use AttributesCommandTrait;
    use GetIdCommandTrait;

    private int $id;

    private ProjectPermissionRole $role;

    public function __construct(int $id, string $role)
    {
        $this->id = $id;
        $this->role = ProjectPermissionRole::tryFrom($role) ?? ProjectPermissionRole::OPERATOR;
    }

    public function getRole(): ProjectPermissionRole
    {
        return $this->role;
    }
}
