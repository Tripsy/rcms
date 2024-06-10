<?php

namespace App\Models;

use App\Enums\ItemStatus;
use App\Models\Traits\CreatedByRelationTrait;
use App\Models\Traits\StatusScopeTrait;
use App\Models\Traits\UpdatedByRelationTrait;
use App\Models\Traits\UuidScopeTrait;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOneThrough;

class Item extends BaseModel
{
    use CreatedByRelationTrait;
    use StatusScopeTrait;
    use UpdatedByRelationTrait;
    use UuidScopeTrait;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'item';

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
        'status' => ItemStatus::class,
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
        return $this->hasMany(ItemContent::class, 'item_id', 'id');
    }

    /**
     * Get the project that holds this item
     */
    public function project(): HasOneThrough
    {
        return $this->hasOneThrough(Project::class, ProjectBlueprint::class);
    }
}
