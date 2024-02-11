<?php

namespace App\Models;

use App\Enums\DefaultOption;
use App\Models\Traits\UuidScopeTrait;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ItemData extends Model
{
    use HasFactory;
    use UuidScopeTrait;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'item_data';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'uuid',
        'label',
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
        return $this->belongsTo(Item::class, 'uuid', 'uuid');
    }

    /**
     * Scope a query to select items with selected label
     */
    public function scopeLabel(Builder $query, string $label): void
    {
        $query->where('label', $label);
    }

    /**
     * Scope a query to select items marked as active
     */
    public function scopeIsActive(Builder $query): void
    {
        $query->where('is_active', DefaultOption::YES->value);
    }
}
