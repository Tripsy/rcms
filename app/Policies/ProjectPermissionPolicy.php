<?php

namespace App\Policies;

use App\Enums\ProjectPermissionRole;
use App\Models\Project;
use App\Models\ProjectPermission;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class ProjectPermissionPolicy
{
    /**
     * Perform pre-authorization checks.
     *
     * For certain users, you may wish to authorize all actions within a given policy.
     * To accomplish this, define a before method on the policy. The before method will be executed before any other methods on the policy,
     * giving you an opportunity to authorize the action before the intended policy method is actually called.
     * This feature is most commonly used for authorizing application administrators to perform any action
     *
     * f you would like to deny all authorization checks for a particular type of user then you may return false from the before method.
     * If null is returned, the authorization check will fall through to the policy method.
     *
     * The before method of a policy class will not be called if the class doesn't contain a method with a name matching the name of the ability being checked.
     *
     * https://laravel.com/docs/10.x/authorization#policy-filters
     *
     */
    public function before(User $user, string $ability): bool|null
    {
        if ($user->isAdmin()) {
            return true;
        }

        return null; // if null is returned, the authorization check will fall through to the policy method.
    }

    /**
     * Determine whether the user can list the model.
     */
    public function index(User $user, Project $project): Response
    {
        return $project->hasPermission($user)
                    ? Response::allow()
                    : Response::deny(__('message.exception.access_denied'));
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, Project $project): Response
    {
        return $project->hasPermission($user)
                    ? Response::allow()
                    : Response::deny(__('message.exception.access_denied'));
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user, Project $project): Response
    {
        return $project->hasRole($user, ProjectPermissionRole::MANAGER)
                    ? Response::allow()
                    : Response::deny(__('message.exception.access_denied'));
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, ProjectPermission $projectPermission, Project $project): Response
    {
        if ($user->id === $projectPermission->user_id) {
            return Response::deny(__('message.exception.access_denied'));
        }

        if ($project->hasRole($user, ProjectPermissionRole::MANAGER) === false) {
            return Response::deny(__('message.exception.access_denied'));
        }

        return Response::allow();
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, ProjectPermission $projectPermission, Project $project): Response
    {
        if ($user->id === $projectPermission->user_id) {
            return Response::deny(__('message.exception.access_denied'));
        }

        if ($project->hasRole($user, ProjectPermissionRole::MANAGER) === false) {
            return Response::deny(__('message.exception.access_denied'));
        }

        return Response::allow();
    }
}
