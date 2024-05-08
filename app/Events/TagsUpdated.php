<?php

declare(strict_types=1);

namespace App\Events;

use App\Models\Tags;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class TagsUpdated
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public Tags $tags;

    /**
     * Create a new event instance.
     */
    public function __construct(Tags $tags)
    {
        $this->tags = $tags;
    }
}
