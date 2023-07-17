<?php

namespace App\Events;

use App\Models\Account;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class AccountCreated
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public Account $account;

    /**
     * Create a new event instance.
     */
    public function __construct(Account $account)
    {
        $this->account = $account;
    }
}
