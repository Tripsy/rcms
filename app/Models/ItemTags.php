<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * @property-read Item $item
 * @property-read Tag $tag
 */
class ItemTags extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'item_tags';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'item_id',
        'tags_id',
    ];

    /**
     * Get the item that owns this.
     */
    public function item(): BelongsTo
    {
        return $this->belongsTo(Item::class, 'item_id', 'id');
    }

    /**
     * Get the tag that owns this.
     */
    public function tag(): BelongsTo
    {
        return $this->belongsTo(Tag::class, 'tags_id', 'id');
    }
}
