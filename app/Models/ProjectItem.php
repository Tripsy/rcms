<?php

namespace App\Models;

use App\Enums\BlueprintComponentStatus;
use App\Models\Traits\StatusScopeTrait;
use App\Models\Traits\UuidScopeTrait;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOneThrough;

class ProjectItem extends BaseModel
{
    use UuidScopeTrait;
    use StatusScopeTrait;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'project_item';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'uuid',
        'project_blueprint_id',
        'description',
        'status',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'status' => BlueprintComponentStatus::class,
    ];

    /**
     * Get the blueprint that owns this item
     */
    public function blueprint(): BelongsTo
    {
        return $this->belongsTo(ProjectBlueprint::class);
    }

    /**
     * Get the content for the item.
     */
    public function itemContent(): HasMany
    {
        return $this->hasMany(ItemContent::class, 'uuid', 'uuid');
    }

    /**
     * Get the project that holds this item
     */
    public function project(): HasOneThrough
    {
        return $this->hasOneThrough(Project::class, ProjectBlueprint::class);
    }
}
