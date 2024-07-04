<?php

namespace App\Exceptions;

use Exception;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Log;

class ConsoleException extends Exception
{
    public function __construct($message, $code = 0, ?Exception $previous = null)
    {
        // Make sure everything is assigned properly
        parent::__construct($message, $code, $previous);

        // Log the exception message
        $this->logConsoleError($previous);
    }

    protected function logConsoleError(Exception $exception): void
    {
        Log::channel('console')->info(__('console.purge-item-content.failed'), [
            'date' => Carbon::now()->format('Y-m-d H:i'),
            'error' => $exception->getMessage(),
        ]);
    }
}
