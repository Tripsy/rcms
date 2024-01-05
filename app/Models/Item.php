<?php

namespace App\Models;

use App\Enums\ItemStatus;
use App\Models\Traits\StatusScopeTrait;
use App\Models\Traits\UuidScopeTrait;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOneThrough;

class Item extends BaseModel
{
    use UuidScopeTrait;
    use StatusScopeTrait;

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
        'item_type_id',
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
     * Get the item type that owns the item.
     */
    public function itemType(): BelongsTo
    {
        return $this->belongsTo(ItemType::class);
    }

    /**
     * Get the data for the item.
     */
    public function itemData(): HasMany
    {
        return $this->hasMany(ItemData::class, 'uuid', 'uuid');
    }

    /**
     * Get the project that holds this item
     */
    public function project(): HasOneThrough
    {
        return $this->hasOneThrough(Project::class, ItemType::class);
    }
}
