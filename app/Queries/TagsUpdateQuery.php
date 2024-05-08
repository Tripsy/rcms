<?php

declare(strict_types=1);

namespace App\Queries;

use App\Models\Tags;

class TagsUpdateQuery  extends AbstractUpdateQuery
{
    public function __construct()
    {
        $this->query = Tags::query();
    }
}
