<?php

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
