<?php

declare(strict_types=1);

namespace App\Http\Controllers\ProjectBlueprint;

use App\Actions\ProjectBlueprintStore;
use App\Commands\ProjectBlueprintStoreCommand;
use App\Exceptions\ControllerException;
use App\Http\Controllers\Controller;
use App\Http\Requests\ProjectBlueprintIndexRequest;
use App\Http\Requests\ProjectBlueprintStoreRequest;
use App\Models\Project;
use App\Models\ProjectBlueprint;
use App\Queries\ProjectBlueprintReadQuery;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Gate;
use Symfony\Component\HttpFoundation\Response;
use Tripsy\ApiResponse\ApiResponse;

class ApiProjectBlueprintController extends Controller
{
    private ApiResponse $apiResponse;

    public function __construct(ApiResponse $apiResponse)
    {
        $this->apiResponse = $apiResponse;
    }

    /**
     * Display a listing of the resource.
     */
    public function index(
        ProjectBlueprintIndexRequest $request,
        Project $project,
        ProjectBlueprintReadQuery $query
    ): JsonResponse {
        Gate::authorize('index', [ProjectBlueprint::class, $project]);

        $validated = $request->validated();

        $blueprints = $query
            ->filterByProjectId($project->id)
            ->filterByDescription('%'.$validated['filter']['description'].'%', 'LIKE')
            ->filterByNotes('%'.$validated['filter']['description'].'%', 'LIKE')
            ->filterByStatus($validated['filter']['status'])
            ->withCreatedBy()
            ->withUpdatedBy()
            ->get($validated['page'], $validated['limit'])
            ->makeHidden(['user_id']);

        $this->apiResponse->success(true);
        $this->apiResponse->message(__('message.success'));
        $this->apiResponse->data([
            'results' => $blueprints,
            'count' => count($blueprints),
            'limit' => $validated['limit'],
            'page' => $validated['page'],
        ]);

        return response()->json($this->apiResponse->resultArray(), Response::HTTP_OK);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @throws ControllerException
     */
    public function store(
        ProjectBlueprintStoreRequest $request,
        Project $project,
        ProjectBlueprintReadQuery $query
    ): JsonResponse {
        Gate::authorize('create', [ProjectBlueprint::class, $project]);

        $validated = $request->validated();

        $command = new ProjectBlueprintStoreCommand(
            $project->id,
            $validated['description'],
            $validated['notes'],
            $validated['status'],
        );

        ProjectBlueprintStore::run($command);

        make description unique per project id >> migration

        try {
            $blueprint = $query
                ->filterByProjectId($command->getProjectId())
                ->filterByDescription($command->getDescription())
                ->firstOrFail();
        } catch (ModelNotFoundException) {
            throw new ControllerException(
                __('message.project_blueprint.store_fail'),
                Response::HTTP_INTERNAL_SERVER_ERROR
            );
        }

        $this->apiResponse->success(true);
        $this->apiResponse->message(__('message.success'));
        $this->apiResponse->data(array_merge(
            [
                'id' => $blueprint->id,
            ],
            $command->attributes()
        ));

        return response()->json($this->apiResponse->resultArray(), Response::HTTP_CREATED);
    }

    /**
     * Display the specified resource.
     */
    public function show(
        Project $project,
        ProjectBlueprint $projectBlueprint,
        ProjectBlueprintReadQuery $query,
        ProjectBlueprintRepository $repository
    ): JsonResponse {
        Gate::authorize('view', [ProjectBlueprint::class, $project]);

        $data = $repository->getViewCache($projectBlueprint->id, function () use ($query, $projectBlueprint) {
            return $query
                ->filterById($projectBlueprint->id)
                ->withCreatedBy()
                ->withUpdatedBy()
                ->first();
        });

        //TODO add components ?

        $this->apiResponse->success(true);
        $this->apiResponse->message(__('message.success'));
        $this->apiResponse->pushMeta('isCached', $repository->isCached());
        $this->apiResponse->data($data);

        return response()->json($this->apiResponse->resultArray(), Response::HTTP_OK);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(
        ProjectBlueprintUpdateRequest $request,
        Project $project,
        ProjectBlueprint $projectBlueprint
    ): JsonResponse {
        Gate::authorize('update', [ProjectBlueprint::class, $project]);

        $validated = $request->validated();

        $command = new ProjectBlueprintUpdateCommand(
            $projectBlueprint->id,
            $validated['role']
        );

        ProjectBlueprintUpdate::run($command);

        $this->apiResponse->success(true);
        $this->apiResponse->message(__('message.success'));
        $this->apiResponse->data($command->attributes());

        return response()->json($this->apiResponse->resultArray(), Response::HTTP_OK);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Project $project, ProjectBlueprint $projectBlueprint): JsonResponse
    {
        Gate::authorize('delete', [ProjectBlueprint::class, $project]);

        $command = new ProjectBlueprintDeleteCommand(
            $projectBlueprint->id
        );

        ProjectBlueprintDelete::run($command);

        $this->apiResponse->success(true);
        $this->apiResponse->message(__('message.success'));
        $this->apiResponse->data($command->attributes());

        return response()->json($this->apiResponse->resultArray(), Response::HTTP_NO_CONTENT);
    }
}
