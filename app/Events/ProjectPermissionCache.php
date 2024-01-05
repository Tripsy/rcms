<?php

namespace App\Events;

use App\Models\ProjectPermission;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class ProjectPermissionCache
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
