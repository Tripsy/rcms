<?php

namespace App\Models;

use App\Enums\ItemStatus;
use App\Models\Traits\StatusScopeTrait;
use App\Models\Traits\UuidScopeTrait;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

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
        'account_id',
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

//    /**
//     * The "booted" method of the model.
//     */
//    protected static function booted(): void
//    {
//        static::addGlobalScope(new ActiveScope);
//    }

//    /**
//     * Get the user that owns the phone.
//     */
//    public function user(): BelongsTo
//    {
//        return $this->belongsTo(User::class);
//    }
}
