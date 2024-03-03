<?php

declare(strict_types=1);

namespace App\Commands;

use App\Commands\Traits\AttributesCommandTrait;
use App\Commands\Traits\GetStatusCommandTrait;
use App\Commands\Traits\GetUuidCommandTrait;
use App\Enums\BlueprintComponentFormat;
use App\Enums\BlueprintComponentType;
use App\Enums\CommonStatus;

class BlueprintComponentStoreCommand
{
    use AttributesCommandTrait;
    use GetUuidCommandTrait;
    use GetStatusCommandTrait;

    private string $uuid;

    private int $project_id;

    private string $description;

    private CommonStatus $status;

    public function __construct(
        string $uuid,
        int $project_blueprint_id,
        string $name,
        string $description,
        string $info,
        BlueprintComponentType $component_type,
        BlueprintComponentFormat $component_format,
        ??
        CommonStatus $status)
    {
        $this->project_blueprint_id = $project_blueprint_id;
        $this->uuid = $uuid;
        $this->name = $name;
        $this->description = $description;
        $this->info = $info;
        $this->component_type = $component_type;
        $this->component_format = $component_format;
        $this->type_options = $type_options;
        $this->is_required = $is_required;
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
