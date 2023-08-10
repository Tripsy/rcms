<?php

namespace App\Http\Controllers\Project;

use App\Commands\ProjectPermissionStoreCommand;
use App\Enums\CommonStatus;
use App\Enums\ProjectPermissionRole;
use App\Exceptions\ControllerException;
use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class ApiProjectPermissionController extends Controller
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
    public function store(ProjectPermissionStoreRequest $request, ProjectPermissionRepositoryInterface $projectRepository): JsonResponse
    {
        $validated = $request->validated();

        $commandProjectPermission = new ProjectPermissionStoreCommand(
            $validated['project_id'],
            $validated['user_id'],
            ProjectPermissionRole::from($validated['role'])  ??
            empty($validated['status']) === false ? CommonStatus::from($validated['status']) : CommonStatus::ACTIVE cast to status directly from validation
        );

        ProjectPermissionStore::dispatchSync($commandProjectPermission);

//        try {
//            $project = $projectRepository->findByAuthority($commandProject->getAuthorityName(), $commandProject->getAuthorityKey());
//        } catch (ModelNotFoundException) {
//            throw new ControllerException(__('message.project.store_fail'), Response::HTTP_INTERNAL_SERVER_ERROR);
//        }

        return response()->json([
            'success' => true,
            'message' => __('message.success'),
            'data' => [
                'id' => $project->id,
                'name' => $commandProject->getName(),
                'authority_name' => $commandProject->getAuthorityName(),
                'authority_key' => $commandProject->getAuthorityKey(),
            ]
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
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
