<?php

namespace App\Models;

use App\Enums\CommonStatus;
use App\Models\Traits\CreatedByRelationTrait;
use App\Models\Traits\StatusScopeTrait;
use App\Models\Traits\UpdatedByRelationTrait;
use App\Models\Traits\UuidScopeTrait;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class ProjectBlueprint extends BaseModel
{
    use CreatedByRelationTrait;
    use UpdatedByRelationTrait;
    use StatusScopeTrait;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'project_blueprint';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'project_id',
        'name',
        'description',
        'notes',
        'status',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'status' => CommonStatus::class,
    ];

    /**
     * Get the project that owns this blueprint.
     */
    public function project(): BelongsTo
    {
        return $this->belongsTo(Project::class);
    }

    /**
     * Get the components for this blueprint.
     */
    public function components(): HasMany
    {
        return $this->hasMany(BlueprintComponent::class);
    }
}
