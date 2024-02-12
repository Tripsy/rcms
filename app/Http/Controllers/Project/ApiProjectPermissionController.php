<?php

declare(strict_types=1);

namespace App\Http\Controllers\Project;

use App\Actions\ProjectPermissionDelete;
use App\Actions\ProjectPermissionStore;
use App\Actions\ProjectPermissionUpdate;
use App\Commands\ProjectPermissionDeleteCommand;
use App\Commands\ProjectPermissionStoreCommand;
use App\Commands\ProjectPermissionUpdateCommand;
use App\Exceptions\ControllerException;
use App\Http\Controllers\Controller;
use App\Http\Requests\ProjectPermissionIndexRequest;
use App\Http\Requests\ProjectPermissionStoreRequest;
use App\Http\Requests\ProjectPermissionUpdateRequest;
use App\Models\Project;
use App\Models\ProjectPermission;
use App\Queries\ProjectPermissionReadQuery;
use App\Repositories\ProjectPermissionRepository;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Gate;
use Symfony\Component\HttpFoundation\Response;

class ApiProjectPermissionController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(
        ProjectPermissionIndexRequest $request,
        Project $project,
        ProjectPermissionReadQuery $query,
        ProjectPermissionRepository $repository
    ): JsonResponse {
        Gate::authorize('index', [ProjectPermission::class, $project]);

        $validated = $request->validated();

        $permissions = $query
            ->filterByProjectId($project->id)
            ->filterByUserName('%'.$validated['filter']['user_name'].'%', 'LIKE')
            ->filterByRole($validated['filter']['role'])
            ->filterByStatus($validated['filter']['status'])
            ->withUser()
            ->withCreatedBy()
            ->withUpdatedBy()
            ->get($validated['page'], $validated['limit'])
            ->makeHidden(['user_id']);

        return response()->json([
            'success' => true,
            'message' => __('message.success'),
            'data' => [
                'results' => $permissions,
                'count' => count($permissions),
                'limit' => $validated['limit'],
                'page' => $validated['page'],
            ],
        ], Response::HTTP_OK);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @throws ControllerException
     */
    public function store(
        ProjectPermissionStoreRequest $request,
        Project $project,
        ProjectPermissionReadQuery $query
    ): JsonResponse {
        Gate::authorize('create', [ProjectPermission::class, $project]);

        $validated = $request->validated();

        $command = new ProjectPermissionStoreCommand(
            $project->id,
            $validated['user_id'],
            $validated['role'],
            $validated['status'],
        );

        ProjectPermissionStore::run($command);

        try {
            $permission = $query
                ->filterByProjectId($command->getProjectId())
                ->filterByUserId($command->getUserId())
                ->firstOrFail();
        } catch (ModelNotFoundException) {
            throw new ControllerException(
                __('message.project_permission.store_fail'),
                Response::HTTP_INTERNAL_SERVER_ERROR
            );
        }

        return response()->json([
            'success' => true,
            'message' => __('message.success'),
            'data' => array_merge(
                [
                    'id' => $permission->id,
                ],
                $command->attributes()
            ),
        ], Response::HTTP_CREATED);
    }

    /**
     * Display the specified resource.
     */
    public function show(
        Project $project,
        ProjectPermission $projectPermission,
        ProjectPermissionReadQuery $query,
        ProjectPermissionRepository $repository
    ): JsonResponse {
        Gate::authorize('view', [ProjectPermission::class, $project]);

        $data = $repository->getViewCache($projectPermission->id, function () use ($query, $projectPermission) {
            return $query
                ->filterById($projectPermission->id)
                ->withCreatedBy()
                ->withUpdatedBy()
                ->first();
        });

        return response()->json([
            'success' => true,
            'message' => __('message.success'),
            'is_cached' => $repository->isCached(),
            'data' => $data,
        ], Response::HTTP_OK);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(
        ProjectPermissionUpdateRequest $request,
        Project $project,
        ProjectPermission $projectPermission
    ): JsonResponse {
        Gate::authorize('update', [$projectPermission, $project]);

        $validated = $request->validated();

        $command = new ProjectPermissionUpdateCommand(
            $projectPermission->id,
            $validated['role']
        );

        ProjectPermissionUpdate::run($command);

        return response()->json([
            'success' => true,
            'message' => __('message.success'),
            'data' => $command->attributes(),
        ], Response::HTTP_OK);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Project $project, ProjectPermission $projectPermission): JsonResponse
    {
        Gate::authorize('delete', [$projectPermission, $project]);

        $command = new ProjectPermissionDeleteCommand(
            $projectPermission->id
        );

        ProjectPermissionDelete::run($command);

        return response()->json([
            'success' => true,
            'message' => __('message.success'),
        ], Response::HTTP_OK);
    }
}
