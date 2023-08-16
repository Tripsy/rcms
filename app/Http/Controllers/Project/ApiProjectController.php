<?php

declare(strict_types=1);

namespace App\Http\Controllers\Project;

use App\Actions\ProjectDelete;
use App\Commands\ProjectDeleteCommand;
use App\Commands\ProjectStoreCommand;
use App\Commands\ProjectUpdateCommand;
use App\Exceptions\ControllerException;
use App\Http\Controllers\Controller;
use App\Http\Requests\ProjectIndexRequest;
use App\Http\Requests\ProjectStoreRequest;
use App\Http\Requests\ProjectUpdateRequest;
use App\Actions\ProjectStore;
use App\Actions\ProjectUpdate;
use App\Models\Project;
use App\Queries\ProjectFirstQuery;
use App\Queries\ProjectGetQuery;
use App\Repositories\Interfaces\ProjectRepositoryInterface;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Gate;
use Symfony\Component\HttpFoundation\Response;

class ApiProjectController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(ProjectIndexRequest $request, ProjectGetQuery $projects): JsonResponse
    {
        Gate::authorize('index', Project::class);

        $validated = $request->validated();
        $validated['page'] = (int) $validated['page'];
        $validated['limit'] = (int) $validated['limit'];

        $projects = $projects
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
                'page' => $validated['page']
            ]
        ], Response::HTTP_OK);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @throws ControllerException
     */
    public function store(ProjectStoreRequest $request, ProjectRepositoryInterface $projectRepository): JsonResponse
    {
        Gate::authorize('create', Project::class);

        $validated = $request->validated();

        $commandProject = new ProjectStoreCommand(
            $validated['name'],
            $validated['authority_name'],
            $validated['authority_key'],
            $validated['status'] ?? '',
        );

        ProjectStore::run($commandProject);

        try {
            $project = $projectRepository->findByAuthority($commandProject->getAuthorityName(), $commandProject->getAuthorityKey());
        } catch (ModelNotFoundException) {
            throw new ControllerException(__('message.project.store_fail'), Response::HTTP_INTERNAL_SERVER_ERROR);
        }

        return response()->json([
            'success' => true,
            'message' => __('message.success'),
            'data' => array_merge(
                [
                    'id' => $project->id,
                ],
                $commandProject->attributes()
            )
        ], Response::HTTP_CREATED);
    }

    /**
     * Display the specified resource.
     */
    public function show(Project $project, ProjectFirstQuery $projectFirstQuery): JsonResponse
    {
        Gate::authorize('view', $project);

        $project = $projectFirstQuery
            ->filterById($project->id)
            ->withCreatedBy()
            ->withUpdatedBy()
            ->first();

        return response()->json([
            'success' => true,
            'message' => __('message.success'),
            'data' => $project
        ], Response::HTTP_OK);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(ProjectUpdateRequest $request, Project $project): JsonResponse
    {
        Gate::authorize('update', $project);

        $validated = $request->validated();

        $commandProject = new ProjectUpdateCommand(
            $project->id,
            $validated['name'],
            $validated['authority_name'],
            $validated['authority_key']
        );

        ProjectUpdate::run($commandProject);

        return response()->json([
            'success' => true,
            'message' => __('message.success'),
            'data' => $commandProject->attributes()
        ], Response::HTTP_OK);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Project $project): JsonResponse
    {
        Gate::authorize('delete', $project);

        $commandProject = new ProjectDeleteCommand(
            $project->id
        );

        ProjectDelete::run($commandProject);

        return response()->json([
            'success' => true,
            'message' => __('message.success'),
        ], Response::HTTP_OK);
    }
}
