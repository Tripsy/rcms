<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

abstract class BaseModel extends Model
{
    use HasFactory;

    public function getFillableChanges(): array
    {
        return array_intersect_key($this->getChanges(), array_flip($this->getFillable()));
    }
}
