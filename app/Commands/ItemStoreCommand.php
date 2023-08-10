<?php

declare(strict_types=1);

namespace App\Commands;

use App\Commands\Traits\AttributesCommandTrait;
use App\Commands\Traits\GetStatusCommandTrait;
use App\Commands\Traits\GetUuidCommandTrait;
use App\Enums\ItemStatus;

class ItemStoreCommand
{
    use AttributesCommandTrait;
    use GetUuidCommandTrait;
    use GetStatusCommandTrait;

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

    public function getProjectId(): int
    {
        return $this->project_id;
    }

    public function getDescription(): string
    {
        return $this->description;
    }
}
