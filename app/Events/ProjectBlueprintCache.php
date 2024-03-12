<?php

namespace App\Events;

use App\Models\ProjectBlueprint;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class ProjectBlueprintCache
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public ProjectBlueprint $blueprint;

    /**
     * Create a new event instance.
     */
    public function __construct(ProjectBlueprint $blueprint)
    {
        $this->blueprint = $blueprint;
    }
}