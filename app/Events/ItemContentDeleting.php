<?php

declare(strict_types=1);

namespace App\Events;

use App\Models\ItemContent;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class ItemContentDeleting
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public ItemContent $itemContent;

    /**
     * Create a new event instance.
     */
    public function __construct(ItemContent $itemContent)
    {
        $this->itemContent = $itemContent;
    }
}
