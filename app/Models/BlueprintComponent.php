<?php

namespace App\Models;

use App\Enums\CommonStatus;
use App\Enums\DefaultOption;
use App\Enums\BlueprintComponentType;
use App\Models\Traits\StatusScopeTrait;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ItemTypeLabel extends BaseModel
{
    use StatusScopeTrait;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'item_type_label';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'item_type_id',
        'label_key',
        'label_name',
        'label_info',
        'label_type',
        'label_options',
        'is_required',
        'is_html',
        'status',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'label_type' => BlueprintComponentType::class,
        'label_options' => 'array',
        'is_required' => DefaultOption::class,
        'is_html' => DefaultOption::class,
        'status' => CommonStatus::class,
    ];

    /**
     * Get the item type that owns this item type label.
     */
    public function itemType(): BelongsTo
    {
        return $this->belongsTo(ItemType::class);
    }
}
