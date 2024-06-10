<?php

declare(strict_types=1);

namespace App\Commands;

use App\Commands\Traits\AttributesCommandTrait;
use App\Commands\Traits\GetDescriptionCommandTrait;
use App\Commands\Traits\GetStatusCommandTrait;
use App\Commands\Traits\GetUuidCommandTrait;
use App\Enums\ItemStatus;
use Illuminate\Support\Str;

class ItemStoreCommand
{
    use AttributesCommandTrait;
    use GetDescriptionCommandTrait;
    use GetStatusCommandTrait;
    use GetUuidCommandTrait;

    private string $uuid;

    private int $project_blueprint_id;

    private string $description;

    private ItemStatus $status;

    public function __construct(int $project_blueprint_id, string $description, string $status)
    {
        $this->uuid = (string) Str::orderedUuid();
        $this->project_blueprint_id = $project_blueprint_id;
        $this->description = $description;
        $this->status = ItemStatus::tryFrom($status) ?? ItemStatus::DRAFT;
    }

    public function getProjectBlueprintId(): int
    {
        return $this->project_blueprint_id;
    }
}
