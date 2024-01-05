<?php

namespace App\Models;

use App\Enums\CommonStatus;
use App\Enums\ProjectPermissionRole;
use App\Models\Traits\CreatedByRelationTrait;
use App\Models\Traits\StatusScopeTrait;
use App\Models\Traits\UpdatedByRelationTrait;
use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Project extends BaseModel
{
    use CreatedByRelationTrait;
    use UpdatedByRelationTrait;
    use StatusScopeTrait;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'project';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name',
        'authority_name',
        'authority_key',
        'status',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'status' => CommonStatus::class,
    ];

    /**
     * The event map for the model.
     *
     * Eloquent models dispatch several events, allowing you to hook into the following moments in a model's lifecycle: retrieved, creating, created, updating, updated,
     * saving, saved, deleting, deleted, trashed, forceDeleting, forceDeleted, restoring, restored, and replicating.
     *
     * If you are listening for many events on a given model, you may use observers to group all of your listeners into a single class.
     * Observer classes have method names which reflect the Eloquent events you wish to listen for. Each of these methods receives the affected model as their only argument.
     *
     * @var array
     */
    protected $dispatchesEvents = [
//        'created' => ProjectCreated::class, // replaced by use of app/Observers/ProjectObserver.php
    ];

    /**
     * Get the item type for this project.
     */
    public function itemTypes(): HasMany
    {
        return $this->hasMany(ItemType::class);
    }

    /**
     * Get the permissions for this project.
     */
    public function permissions(): HasMany
    {
        return $this->hasMany(ProjectPermission::class);
    }

    /**
     * Check if user has permission (active) with specified role on the project
     *
     * @param User|Authenticatable $user
     * @param ProjectPermissionRole $role
     * @return bool
     */
    public function hasRole(User|Authenticatable $user, ProjectPermissionRole $role): bool
    {
        return $this->permissions()
                    ->where('user_id', $user->id)
                    ->where('role', $role)
                    ->where('status', CommonStatus::ACTIVE)
                    ->exists();
    }

    /**
     * Check if user has permission (active) set on the project
     *
     * @param User|Authenticatable $user
     * @return bool
     */
    public function hasPermission(User|Authenticatable $user): bool
    {
        return $this->permissions()
                    ->where('user_id', $user->id)
                    ->where('status', CommonStatus::ACTIVE)
                    ->exists();
    }
}
