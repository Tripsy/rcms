<?php

namespace App\Models;

use App\Enums\CommonStatus;
use App\Enums\DefaultOption;
use App\Models\Traits\CreatedByRelationTrait;
use App\Models\Traits\StatusScopeTrait;
use App\Models\Traits\UpdatedByRelationTrait;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * @property-read Project $project
 * @property-read \Illuminate\Database\Eloquent\Collection|Item[] $items
 */
class Tag extends BaseModel
{
    use CreatedByRelationTrait;
    use UpdatedByRelationTrait;
    use StatusScopeTrait;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'tags';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'project_id',
        'name',
        'description',
        'is_category',
        'status',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'is_category' => DefaultOption::class,
        'status' => CommonStatus::class,
    ];

    /**
     * Get the project that owns this tag.
     */
    public function project(): BelongsTo
    {
        return $this->belongsTo(Project::class);
    }

    /**
     * Get the items for this tag.
     */
    public function items(): HasMany
    {
        return $this->hasMany(Item::class);
    }
}
