<?php

namespace App\Models;

use App\Enums\CommonStatus;
use App\Enums\ProjectPermissionRole;
use App\Models\Traits\CreatedByRelationTrait;
use App\Models\Traits\StatusScopeTrait;
use App\Models\Traits\UpdatedByRelationTrait;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ProjectPermission extends BaseModel
{
    use CreatedByRelationTrait;
    use UpdatedByRelationTrait;
    use StatusScopeTrait;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'project_permission';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'project_id',
        'user_id',
        'role',
        'status'
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'role' => ProjectPermissionRole::class,
        'status' => CommonStatus::class,
    ];

    /**
     * Get the project that owns the permission.
     */
    public function project(): BelongsTo
    {
        return $this->belongsTo(Project::class);
    }

    /**
     * Get the user that owns the permission.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
