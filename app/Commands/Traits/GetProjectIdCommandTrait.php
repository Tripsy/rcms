<?php

declare(strict_types=1);

namespace App\Commands\Traits;

trait GetProjectIdCommandTrait
{
    /**
     * Return attribute `project_id`
     */
    public function getProjectId(): int
    {
        return $this->project_id;
    }
}
