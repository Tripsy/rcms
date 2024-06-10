<?php

declare(strict_types=1);

namespace App\Events;

use App\Models\Item;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class ItemCache
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public Item $item;

    /**
     * Create a new event instance.
     */
    public function __construct(Item $item)
    {
        $this->item = $item;
    }
}
