<?php

declare(strict_types=1);

namespace App\Commands;

use App\Commands\Traits\AttributesCommandTrait;
use App\Commands\Traits\GetStatusCommandTrait;
use App\Enums\CommonStatus;

class ProjectBlueprintStoreCommand
{
    use AttributesCommandTrait;
    use GetStatusCommandTrait;

    private int $project_id;

    private string $description;

    private ?string $notes;

    private CommonStatus $status;

    public function __construct(int $project_id, string $description, ?string $notes, string $status)
    {
        $this->project_id = $project_id;
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
