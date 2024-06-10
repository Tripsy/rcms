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
use App\Queries\ProjectQuery;
use App\Repositories\ProjectRepository;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Gate;
use Symfony\Component\HttpFoundation\Response;
use Tripsy\ApiWrapper\ApiWrapper;

class ApiProjectController extends Controller
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
        ProjectIndexRequest $request,
        ProjectQuery $query
    ): JsonResponse {
        Gate::authorize('index', Project::class);

        $validated = $request->validated();

        $results = $query
            ->whereHasPermission()
            ->filterByName($validated['filter']['name'])
            ->filterByAuthorityName($validated['filter']['authority_name'])
            ->filterByStatus($validated['filter']['status'])
            ->withCreatedBy()
            ->withUpdatedBy()
            ->get($validated['page'], $validated['limit']);

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
    public function store(ProjectStoreRequest $request, ProjectQuery $query): JsonResponse
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

        $this->apiWrapper->success(true);
        $this->apiWrapper->message(__('message.success'));
        $this->apiWrapper->data(array_merge(
            [
                'id' => $project->id,
            ],
            $command->attributes()
        ));

        return response()->json($this->apiWrapper->resultArray(), Response::HTTP_CREATED);
    }

    /**
     * Display the specified resource.
     */
    public function show(Project $project, ProjectQuery $query, ProjectRepository $repository): JsonResponse
    {
        Gate::authorize('view', $project);

        $data = $repository->getViewCache($project->id, function () use ($query, $project) {
            return $query
                ->filterById($project->id)
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

        $this->apiWrapper->success(true);
        $this->apiWrapper->message(__('message.success'));
        $this->apiWrapper->data($command->attributes());

        return response()->json($this->apiWrapper->resultArray(), Response::HTTP_OK);
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

        $this->apiWrapper->success(true);
        $this->apiWrapper->message(__('message.success'));
        $this->apiWrapper->data($command->attributes());

        return response()->json($this->apiWrapper->resultArray(), Response::HTTP_ACCEPTED);
    }
}
