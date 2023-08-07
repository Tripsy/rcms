<?php

namespace App\Models;

use App\Enums\ProjectStatus;
use App\Events\ProjectCreated;
use App\Models\Traits\StatusScopeTrait;

class Project extends BaseModel
{
    use StatusScopeTrait;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'project';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name',
        'authority_name',
        'authority_key',
        'status',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'status' => ProjectStatus::class,
    ];

    /**
     * The event map for the model.
     *
     * @var array
     */
    protected $dispatchesEvents = [
        'created' => ProjectCreated::class,
    ];
}
