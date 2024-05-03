<?php

namespace App\Models;

use App\Enums\DefaultOption;
use App\Models\Traits\UuidScopeTrait;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ItemContent extends Model
{
    use HasFactory;
    use UuidScopeTrait;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'item_content';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'blueprint_item_id',
        'blueprint_component_id',
        'content',
        'is_active',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'is_active' => DefaultOption::class,
    ];

    /**
     * Get the item that owns this item data.
     */
    public function item(): BelongsTo
    {
        return $this->belongsTo(BlueprintItem::class, 'blueprint_item_id', 'id');
    }

    /**
     * Scope a query to select items with selected label
     */
    public function component(): BelongsTo
    {
        return $this->belongsTo(BlueprintComponent::class, 'blueprint_component_id', 'id');
    }

    /**
     * Scope a query to select items marked as active
     */
    public function scopeActive(Builder $query): void
    {
        $query->where('is_active', DefaultOption::YES->value);
    }
}
