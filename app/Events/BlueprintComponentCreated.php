<?php

namespace App\Events;

use App\Models\BlueprintComponent;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class BlueprintComponentCreated
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public BlueprintComponent $component;

    /**
     * Create a new event instance.
     */
    public function __construct(BlueprintComponent $component)
    {
        $this->component = $component;
    }
}
