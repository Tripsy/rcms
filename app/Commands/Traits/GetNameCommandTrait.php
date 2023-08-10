<?php

namespace App\Commands\Traits;

trait GetNameCommandTrait
{
    /**
     * Return attribute `name`
     */
    public function getName(): string
    {
        return $this->name;
    }
}
