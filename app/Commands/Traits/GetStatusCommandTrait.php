<?php

namespace App\Commands\Traits;

use BackedEnum;

trait GetStatusCommandTrait
{
    /**
     * Return attribute `status`
     */
    public function getStatus(): BackedEnum
    {
        return $this->status;
    }
}
