<?php

declare(strict_types=1);

namespace App\Http\Controllers\Project;

use App\Actions\ProjectDelete;
use App\Actions\ProjectPermissionStore;
use App\Actions\ProjectStore;
use App\Actions\ProjectUpdate;
use App\Commands\ProjectDeleteCommand;
use App\Commands\ProjectPermissionStoreCommand;
use App\Commands\ProjectStoreCommand;
use App\Commands\ProjectUpdateCommand;
use App\Enums\CommonStatus;
use App\Enums\ProjectPermissionRole;
use App\Exceptions\ControllerException;
use App\Http\Controllers\Controller;
use App\Http\Requests\ProjectIndexRequest;
use App\Http\Requests\ProjectStoreRequest;
use App\Http\Requests\ProjectUpdateRequest;
use App\Models\Project;
use App\Queries\ProjectReadQuery;
use App\Repositories\ProjectRepository;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Gate;
use Symfony\Component\HttpFoundation\Response;

class ApiProjectController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(
        ProjectIndexRequest $request,
        ProjectReadQuery $query,
        ProjectRepository $repository
    ): JsonResponse {
        Gate::authorize('index', Project::class);

        $validated = $request->validated();

        $projects = $query
            ->whereHasPermission()
            ->filterByAuthorityName($validated['filter']['authority_name'])
            ->filterByStatus($validated['filter']['status'])
            ->withCreatedBy()
            ->withUpdatedBy()
            ->get($validated['page'], $validated['limit']);

        return response()->json([
            'success' => true,
            'message' => __('message.success'),
            'data' => [
                'results' => $projects,
                'count' => count($projects),
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
    public function store(ProjectStoreRequest $request, ProjectReadQuery $query): JsonResponse
    {
        Gate::authorize('create', Project::class);

        $validated = $request->validated();

        $command = new ProjectStoreCommand(
            $validated['name'],
            $validated['authority_name'],
            $validated['authority_key'],
            $validated['status']
        );

        ProjectStore::run($command);

        try {
            $project = $query
                ->filterByAuthorityName($command->getAuthorityName())
                ->filterByName($command->getName())
                ->firstOrFail();
        } catch (ModelNotFoundException) {
            throw new ControllerException(__('message.project.store_fail'), Response::HTTP_INTERNAL_SERVER_ERROR);
        }

        $commandPermission = new ProjectPermissionStoreCommand(
            $project->id,
            auth()->id(),
            ProjectPermissionRole::MANAGER->value,
            CommonStatus::ACTIVE->value,
        );

        ProjectPermissionStore::run($commandPermission);

        return response()->json([
            'success' => true,
            'message' => __('message.success'),
            'data' => array_merge(
                [
                    'id' => $project->id,
                ],
                $command->attributes()
            ),
        ], Response::HTTP_CREATED);
    }

    /**
     * Display the specified resource.
     */
    public function show(Project $project, ProjectReadQuery $query, ProjectRepository $repository): JsonResponse
    {
        Gate::authorize('view', $project);

        $data = $repository->getViewCache($project->id, function () use ($query, $project) {
            return $query
                ->filterById($project->id)
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
    public function update(ProjectUpdateRequest $request, Project $project): JsonResponse
    {
        Gate::authorize('update', $project);

        $validated = $request->validated();

        $command = new ProjectUpdateCommand(
            $project->id,
            $validated['name'],
            $validated['authority_name'],
            $validated['authority_key']
        );

        ProjectUpdate::run($command);

        return response()->json([
            'success' => true,
            'message' => __('message.success'),
            'data' => $command->attributes(),
        ], Response::HTTP_OK);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Project $project): JsonResponse
    {
        Gate::authorize('delete', $project);

        $command = new ProjectDeleteCommand(
            $project->id
        );

        ProjectDelete::run($command);

        return response()->json([
            'success' => true,
            'message' => __('message.success'),
        ], Response::HTTP_OK);
    }
}
