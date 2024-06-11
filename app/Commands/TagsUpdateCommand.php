<?php

declare(strict_types=1);

namespace App\Commands;

use App\Commands\Traits\AttributesCommandTrait;
use App\Commands\Traits\GetIdCommandTrait;
use App\Commands\Traits\GetNameCommandTrait;
use App\Commands\Traits\GetProjectIdCommandTrait;
use App\Enums\DefaultOption;

class TagsUpdateCommand
{
    use AttributesCommandTrait;
    use GetIdCommandTrait;
    use GetNameCommandTrait;
    use GetProjectIdCommandTrait;

    private int $id;
    
    private int $project_id;

    private string $name;

    private ?string $description;

    private ?DefaultOption $is_category = null;

    public function __construct(int $id, int $project_id, string $name, ?string $description, ?string $is_category)
    {
        $this->id = $id;
        $this->project_id = $project_id;
        $this->name = $name;
        $this->description = $description;

        if ($is_category) {
            $this->is_category = DefaultOption::tryFrom($is_category) ?? null;
        }
    }

    /**
     * Return attribute `name`
     */
    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function getIsCategory(): ?DefaultOption
    {
        return $this->is_category;
    }
}
