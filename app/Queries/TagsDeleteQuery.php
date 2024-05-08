<?php

declare(strict_types=1);

namespace App\Queries;

use App\Models\Tags;

class TagsDeleteQuery  extends AbstractDeleteQuery
{
    public function __construct()
    {
        $this->query = Tags::query();
    }
}
