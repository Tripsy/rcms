<?php

declare(strict_types=1);

namespace App\Commands;

use App\Commands\Traits\AttributesCommandTrait;
use App\Commands\Traits\GetDescriptionCommandTrait;
use App\Commands\Traits\GetNameCommandTrait;
use App\Commands\Traits\GetStatusCommandTrait;
use App\Enums\CommonStatus;
use App\Enums\DefaultOption;
use Illuminate\Support\Str;

class TagsStoreCommand
{
    use AttributesCommandTrait;
    use GetDescriptionCommandTrait;
    use GetNameCommandTrait;
    use GetStatusCommandTrait;

    private int $project_id;

    private string $name;

    private string $description;

    private DefaultOption $is_category;

    private CommonStatus $status;

    public function __construct(
        int $project_id,
        string $name,
        string $description,
        string $is_category,
        string $status
    ) {
        $this->project_id = $project_id;
        $this->name = strtolower($name);
        $this->description = $description;
        $this->is_category = DefaultOption::tryFrom($is_category) ?? DefaultOption::NO;
        $this->status = CommonStatus::tryFrom($status) ?? CommonStatus::ACTIVE;

        if ($this->is_category === DefaultOption::YES) {
            $this->name = Str::title($this->name);
        }
    }

    public function getProjectId(): int
    {
        return $this->project_id;
    }

    public function getIsCategory(): DefaultOption
    {
        return $this->is_category;
    }
}
