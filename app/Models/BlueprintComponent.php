<?php

namespace App\Models;

use App\Enums\BlueprintComponentFormat;
use App\Enums\BlueprintComponentType;
use App\Enums\CommonStatus;
use App\Enums\DefaultOption;
use App\Models\Traits\CreatedByRelationTrait;
use App\Models\Traits\StatusScopeTrait;
use App\Models\Traits\UpdatedByRelationTrait;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class BlueprintComponent extends BaseModel
{
    use CreatedByRelationTrait;
    use StatusScopeTrait;
    use UpdatedByRelationTrait;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'blueprint_component';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'project_blueprint_id',
        'name',
        'description',
        'info',
        'component_type',
        'component_format',
        'type_options',
        'is_required',
        'status',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'component_type' => BlueprintComponentType::class,
        'component_format' => BlueprintComponentFormat::class,
        'type_options' => 'json',
        'is_required' => DefaultOption::class,
        'status' => CommonStatus::class,
    ];

    /**
     * Get the blueprint that owns this component
     */
    public function itemType(): BelongsTo
    {
        return $this->belongsTo(ProjectBlueprint::class);
    }
}
