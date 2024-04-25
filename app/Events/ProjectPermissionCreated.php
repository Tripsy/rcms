<?php

declare(strict_types=1);

namespace App\Events;

use App\Models\ProjectPermission;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class ProjectPermissionCreated
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public ProjectPermission $permission;

    /**
     * Create a new event instance.
     */
    public function __construct(ProjectPermission $permission)
    {
        $this->permission = $permission;
    }
}
