<?php

declare(strict_types=1);

namespace App\Commands;

use App\Commands\Traits\AttributesCommandTrait;
use App\Commands\Traits\GetStatusCommandTrait;
use App\Enums\CommonStatus;
use App\Enums\ProjectPermissionRole;

class ProjectPermissionStoreCommand
{
    use AttributesCommandTrait;
    use GetStatusCommandTrait;

    private int $project_id;
    private int $user_id;
    private ProjectPermissionRole $role;
    private CommonStatus $status;

    public function __construct(int $project_id, int $user_id,  string $role, string $status)
    {
        $this->project_id = $project_id;
        $this->user_id = $user_id;
        $this->role = ProjectPermissionRole::tryFrom($role) ?? ProjectPermissionRole::OPERATOR;
        $this->status = CommonStatus::tryFrom($status) ?? CommonStatus::ACTIVE;
    }

    public function getProjectId(): int
    {
        return $this->project_id;
    }

    public function getUserId(): int
    {
        return $this->user_id;
    }

    public function getRole(): ProjectPermissionRole
    {
        return $this->role;
    }
}
