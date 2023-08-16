<?php

declare(strict_types=1);

namespace App\Commands\Traits;

trait GetUuidCommandTrait
{
    /**
     * Return attribute `uuid`
     */
    public function getUuid(): string
    {
        return $this->uuid;
    }
}
