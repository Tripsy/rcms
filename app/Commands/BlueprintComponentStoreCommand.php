<?php

declare(strict_types=1);

namespace App\Commands;

use App\Commands\Traits\AttributesCommandTrait;
use App\Commands\Traits\GetStatusCommandTrait;
use App\Enums\BlueprintComponentFormat;
use App\Enums\BlueprintComponentType;
use App\Enums\CommonStatus;
use App\Enums\DefaultOption;

class BlueprintComponentStoreCommand
{
    use AttributesCommandTrait;
    use GetStatusCommandTrait;

    private int $project_blueprint_id;

    private string $name;

    private string $description;

    private string $info;

    private BlueprintComponentType $component_type;

    private BlueprintComponentFormat $component_format;

    private array $type_options;

    private DefaultOption $is_required;

    private CommonStatus $status;

    public function __construct(
        int $project_blueprint_id,
        string $name,
        string $description,
        string $info,
        string $component_type,
        string $component_format,
        array $type_options,
        string $is_required,
        string $status
    ) {
        $this->project_blueprint_id = $project_blueprint_id;
        $this->name = $name;
        $this->description = $description;
        $this->info = $info;
        $this->component_type = BlueprintComponentType::tryFrom($component_type) ?? BlueprintComponentType::TEXT;
        $this->component_format = BlueprintComponentFormat::tryFrom($component_format) ?? BlueprintComponentFormat::TEXT;
        $this->type_options = $type_options;
        $this->is_required = DefaultOption::tryFrom($is_required) ?? DefaultOption::NO;
        $this->status = CommonStatus::tryFrom($status) ?? CommonStatus::ACTIVE;
    }

    public function getBlueprintProjectId(): int
    {
        return $this->project_blueprint_id;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getDescription(): string
    {
        return $this->description;
    }

    public function getInfo(): string
    {
        return $this->description;
    }

    public function getComponentType(): BlueprintComponentType
    {
        return $this->component_type;
    }

    public function getComponentFormat(): BlueprintComponentFormat
    {
        return $this->component_format;
    }

    public function getTypeOptions(): array
    {
        return $this->type_options;
    }

    public function getIsRequired(): DefaultOption
    {
        return $this->is_required;
    }
}
