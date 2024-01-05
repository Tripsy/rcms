<?php

declare(strict_types=1);

namespace App\Commands;

use App\Commands\Traits\AttributesCommandTrait;
use App\Commands\Traits\GetNameCommandTrait;
use App\Commands\Traits\GetStatusCommandTrait;
use App\Enums\CommonStatus;

class ProjectStoreCommand
{
    use AttributesCommandTrait;
    use GetNameCommandTrait;
    use GetStatusCommandTrait;

    private string $name;
    private string $authority_name;
    private string $authority_key;
    private CommonStatus $status;

    public function __construct(string $name, string $authority_name, string $authority_key, string $status)
    {
        $this->name = $name;
        $this->authority_name = $authority_name;
        $this->authority_key = $authority_key;
        $this->status = CommonStatus::tryFrom($status) ?? CommonStatus::ACTIVE;
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
