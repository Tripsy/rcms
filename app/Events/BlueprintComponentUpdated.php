<?php

declare(strict_types=1);

namespace App\Events;

use App\Models\BlueprintComponent;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class BlueprintComponentUpdated
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
