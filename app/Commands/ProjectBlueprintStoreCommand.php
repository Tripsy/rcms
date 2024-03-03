<?php

declare(strict_types=1);

namespace App\Commands;

use App\Commands\Traits\AttributesCommandTrait;
use App\Commands\Traits\GetStatusCommandTrait;
use App\Commands\Traits\GetUuidCommandTrait;
use App\Enums\CommonStatus;
use Ramsey\Uuid\UuidInterface;

class ProjectBlueprintStoreCommand
{
    use AttributesCommandTrait;
    use GetUuidCommandTrait;
    use GetStatusCommandTrait;

    private int $project_id;

    private string $uuid;

    private string $description;

    private ?string $notes;

    private CommonStatus $status;

    public function __construct(
        int $project_id,
        string $uuid,
        string $description,
        ?string $notes,
        string $status
    ) {
        $this->project_id = $project_id;
        $this->uuid = $uuid;
        $this->description = $description;
        $this->notes = $notes;
        $this->status = CommonStatus::tryFrom($status) ?? CommonStatus::ACTIVE;
    }

    public function getProjectId(): int
    {
        return $this->project_id;
    }

    public function getDescription(): string
    {
        return $this->description;
    }

    public function getNotes(): ?string
    {
        return $this->notes;
    }
}
