<?php

declare(strict_types=1);

namespace App\Events;

use App\Models\Tag;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class TagsUpdated
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public Tag $tags;

    /**
     * Create a new event instance.
     */
    public function __construct(Tag $tags)
    {
        $this->tags = $tags;
    }
}
