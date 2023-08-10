<?php

namespace App\Commands\Traits;

trait GetIdCommandTrait
{
    /**
     * Return attribute `id`
     */
    public function getId(): string
    {
        return $this->id;
    }
}
