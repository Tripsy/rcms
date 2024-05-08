<?php

declare(strict_types=1);

namespace App\Commands;

use App\Commands\Traits\AttributesCommandTrait;
use App\Commands\Traits\GetIdCommandTrait;
use App\Commands\Traits\GetNameCommandTrait;
use App\Enums\DefaultOption;

class TagsUpdateCommand
{
    use AttributesCommandTrait;
    use GetIdCommandTrait;
    use GetNameCommandTrait;

    private int $id;

    private string $name;

    private ?string $description;

    private ?DefaultOption $is_category = null;

    public function __construct(int $id, string $name, ?string $description, ?string $is_category)
    {
        $this->id = $id;
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
