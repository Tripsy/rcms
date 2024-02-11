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

    private string $authority_key;

    public function __construct(int $id, string $name, string $authority_name, string $authority_key)
    {
        $this->id = $id;
        $this->name = $name;
        $this->authority_name = $authority_name;
        $this->authority_key = $authority_key;
    }

    public function getAuthorityName(): string
    {
        return $this->authority_name;
    }

    public function getAuthorityKey(): string
    {
        return $this->authority_key;
    }
}
