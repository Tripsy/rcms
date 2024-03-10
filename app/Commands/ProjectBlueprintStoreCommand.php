<?php

declare(strict_types=1);

namespace App\Commands;

use App\Commands\Traits\AttributesCommandTrait;
use App\Commands\Traits\GetDescriptionCommandTrait;
use App\Commands\Traits\GetNameCommandTrait;
use App\Commands\Traits\GetStatusCommandTrait;
use App\Enums\CommonStatus;

class ProjectBlueprintStoreCommand
{
    use AttributesCommandTrait;
    use GetNameCommandTrait;
    use GetDescriptionCommandTrait;
    use GetStatusCommandTrait;

    private int $project_id;

    private string $name;

    private ?string $description;

    private CommonStatus $status;

    public function __construct(
        int $project_id,
        string $name,
        ?string $description,
        string $status
    ) {
        $this->project_id = $project_id;
        $this->name = $name;
        $this->description = $description;
        $this->status = CommonStatus::tryFrom($status) ?? CommonStatus::ACTIVE;
    }

    public function getProjectId(): int
    {
        return $this->project_id;
    }
}
