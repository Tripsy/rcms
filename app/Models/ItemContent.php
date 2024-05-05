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
        'item_id',
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
     * Get the item that owns this.
     */
    public function item(): BelongsTo
    {
        return $this->belongsTo(Item::class, 'item_id', 'id');
    }

    /**
     * Get the blueprint component that owns this.
     */
    public function component(): BelongsTo
    {
        return $this->belongsTo(BlueprintComponent::class, 'blueprint_component_id', 'id');
    }

    /**
     * Scope a query to select content marked as active
     */
    public function scopeActive(Builder $query): void
    {
        $query->where('is_active', DefaultOption::YES->value);
    }
}
