<?php

declare(strict_types=1);

namespace App\Http\Controllers\ProjectPermission;

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
use App\Queries\ProjectPermissionQuery;
use App\Repositories\ProjectPermissionRepository;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Gate;
use Symfony\Component\HttpFoundation\Response;
use Tripsy\ApiWrapper\ApiWrapper;

class ApiProjectPermissionController extends Controller
{
    private ApiWrapper $apiWrapper;

    public function __construct(ApiWrapper $apiWrapper)
    {
        $this->apiWrapper = $apiWrapper;
    }

    /**
     * Display a listing of the resource.
     */
    public function index(
        ProjectPermissionIndexRequest $request,
        Project $project,
        ProjectPermissionQuery $query
    ): JsonResponse {
        Gate::authorize('index', [ProjectPermission::class, $project]);

        $validated = $request->validated();

        $results = $query
            ->filterByProjectId($project->id)
            ->filterByUserName('%'.$validated['filter']['user_name'].'%', 'LIKE')
            ->filterByRole($validated['filter']['role'])
            ->filterByStatus($validated['filter']['status'])
            ->withUser()
            ->withCreatedBy()
            ->withUpdatedBy()
            ->get($validated['page'], $validated['limit'])
            ->makeHidden(['user_id']);

        $this->apiWrapper->success(true);
        $this->apiWrapper->message(__('message.success'));
        $this->apiWrapper->data([
            'results' => $results,
            'count' => count($results),
            'limit' => $validated['limit'],
            'page' => $validated['page'],
        ]);

        return response()->json($this->apiWrapper->resultArray(), Response::HTTP_OK);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @throws ControllerException
     */
    public function store(
        ProjectPermissionStoreRequest $request,
        Project $project,
        ProjectPermissionQuery $query
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

        $this->apiWrapper->success(true);
        $this->apiWrapper->message(__('message.success'));
        $this->apiWrapper->data(array_merge(
            [
                'id' => $permission->id,
            ],
            $command->attributes()
        ));

        return response()->json($this->apiWrapper->resultArray(), Response::HTTP_CREATED);
    }

    /**
     * Display the specified resource.
     */
    public function show(
        Project $project,
        ProjectPermission $projectPermission,
        ProjectPermissionQuery $query,
        ProjectPermissionRepository $repository
    ): JsonResponse {
        Gate::authorize('view', [ProjectPermission::class, $project]);

        $data = $repository->getViewCache($projectPermission->id, function () use ($query, $project, $projectPermission) {
            return $query
                ->filterById($projectPermission->id)
                ->filterByProjectId($project->id)
                ->withCreatedBy()
                ->withUpdatedBy()
                ->first();
        });

        $this->apiWrapper->success(true);
        $this->apiWrapper->message(__('message.success'));
        $this->apiWrapper->pushMeta('isCached', $repository->isCached());
        $this->apiWrapper->data($data);

        return response()->json($this->apiWrapper->resultArray(), Response::HTTP_OK);
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
            $project->id,
            $validated['role']
        );

        ProjectPermissionUpdate::run($command);

        $this->apiWrapper->success(true);
        $this->apiWrapper->message(__('message.success'));
        $this->apiWrapper->data($command->attributes());

        return response()->json($this->apiWrapper->resultArray(), Response::HTTP_OK);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Project $project, ProjectPermission $projectPermission): JsonResponse
    {
        Gate::authorize('delete', [$projectPermission, $project]);

        $command = new ProjectPermissionDeleteCommand(
            $projectPermission->id,
            $project->id
        );

        ProjectPermissionDelete::run($command);

        $this->apiWrapper->success(true);
        $this->apiWrapper->message(__('message.success'));
        $this->apiWrapper->data($command->attributes());

        return response()->json($this->apiWrapper->resultArray(), Response::HTTP_ACCEPTED);
    }
}
