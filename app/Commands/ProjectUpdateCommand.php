<?php

declare(strict_types=1);

namespace App\Commands;

use App\Commands\Traits\AttributesCommandTrait;
use App\Commands\Traits\GetIdCommandTrait;
use App\Commands\Traits\GetNameCommandTrait;

class ProjectUpdateCommand
{
    use AttributesCommandTrait;
    use GetIdCommandTrait;
    use GetNameCommandTrait;

    private int $id;
    private string $name;
    private string $authority_name;

    public function __construct(int $id, string $name, string $authority_name)
    {
        $this->id = $id;
        $this->name = $name;
        $this->authority_name = $authority_name;
    }

    public function getAuthorityName(): string
    {
        return $this->authority_name;
    }
}
