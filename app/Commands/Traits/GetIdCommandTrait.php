<?php

declare(strict_types=1);

namespace App\Commands\Traits;

trait GetIdCommandTrait
{
    /**
     * Return attribute `id`
     */
    public function getId(): int
    {
        return $this->id;
    }
}
