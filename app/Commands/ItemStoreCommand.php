<?php

declare(strict_types=1);

namespace App\Commands;

use App\Enums\ItemStatus;

class ItemStoreCommand
{
    private string $uuid;
    private int $project_id;
    private string $description;
    private ItemStatus $status;

    public function __construct(string $uuid, int $project_id, string $description, ItemStatus $status)
    {
        $this->uuid = $uuid;
        $this->project_id = $project_id;
        $this->description = $description;
        $this->status = $status;
    }

    public function getUuid(): string
    {
        return $this->uuid;
    }

    public function getProjectId(): int
    {
        return $this->project_id;
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
