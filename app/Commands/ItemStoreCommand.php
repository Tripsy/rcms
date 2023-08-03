<?php

declare(strict_types=1);

namespace App\Commands;

use App\Enums\ItemStatus;

class ItemStoreCommand
{
    private string $uuid;
    private int $account_id;
    private string $description;
    private ItemStatus $status;

    public function __construct(string $uuid, int $account_id, string $description, ItemStatus $status)
    {
        $this->uuid = $uuid;
        $this->account_id = $account_id;
        $this->description = $description;
        $this->status = $status;
    }

    public function getUuid(): string
    {
        return $this->uuid;
    }

    public function getAccountId(): int
    {
        return $this->account_id;
    }

    public function getDescription(): string
    {
        return $this->description;
    }

    public function getStatus(): ItemStatus
    {
        return $this->status;
    }
}
