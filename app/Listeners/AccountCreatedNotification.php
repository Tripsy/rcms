<?php

namespace App\Listeners;

use App\Events\AccountCreated;
use Illuminate\Support\Facades\Log;

class AccountCreatedNotification
{
    /**
     * Create the event listener.
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     */
    public function handle(AccountCreated $event): void
    {
        Log::channel('test')->info(__('message.account.create'), [
            'id' => $event->account->id
        ]);
    }
}
