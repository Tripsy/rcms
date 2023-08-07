<?php

declare(strict_types=1);

namespace App\Commands;

use App\Enums\AccountStatus;

class AccountStoreCommand
{
    private string $email;
    private AccountStatus $status;

    public function __construct(string $email, AccountStatus $status)
    {
        $this->email = $email;
        $this->status = $status;
    }

    public function getEmail(): string
    {
        return $this->email;
    }

    public function getStatus(): AccountStatus
    {
        return $this->status;
    }
}
