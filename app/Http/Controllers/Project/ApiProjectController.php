<?php

namespace App\Http\Controllers\Project;

use App\Commands\ProjectStoreCommand;
use App\Commands\ProjectUpdateCommand;
use App\Exceptions\ControllerException;
use App\Http\Controllers\Controller;
use App\Http\Requests\ProjectStoreRequest;
use App\Http\Requests\ProjectUpdateRequest;
use App\Jobs\ProjectStore;
use App\Jobs\ProjectUpdate;
use App\Models\Project;
use App\Repositories\Interfaces\ProjectRepositoryInterface;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\JsonResponse;
use Symfony\Component\HttpFoundation\Response;

class ApiProjectController extends Controller
{
    public function __construct()
    {
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     * @throws ControllerException
     */
    public function store(ProjectStoreRequest $request, ProjectRepositoryInterface $projectRepository): JsonResponse
    {
        $validated = $request->validated();

        $commandProject = new ProjectStoreCommand(
            $validated['name'],
            $validated['authority_name'],
            $validated['status'] ?? '',
        );

        ProjectStore::dispatchSync($commandProject);

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
    public function show(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(ProjectUpdateRequest $request, Project $project): JsonResponse
    {
        $validated = $request->validated();

        $commandProject = new ProjectUpdateCommand(
            $project->id,
            $validated['name'],
            $validated['authority_name']
        );

        ProjectUpdate::dispatchSync($commandProject, $project);

        return response()->json([
            'success' => true,
            'message' => __('message.success'),
            'data' => $commandProject->attributes()
        ], Response::HTTP_OK);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
