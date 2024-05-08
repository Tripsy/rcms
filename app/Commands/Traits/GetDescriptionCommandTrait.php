<?php

declare(strict_types=1);

namespace App\Commands\Traits;

trait GetDescriptionCommandTrait
{
    /**
     * Return attribute `description`
     */
    public function getDescription(): string
    {
        return $this->description;
    }
}
