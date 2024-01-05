<?php

declare(strict_types=1);

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
