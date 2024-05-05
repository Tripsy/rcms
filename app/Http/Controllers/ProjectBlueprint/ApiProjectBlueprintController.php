<?php

declare(strict_types=1);

namespace App\Http\Controllers\ProjectBlueprint;

use App\Actions\BlueprintComponentStore;
use App\Actions\ProjectBlueprintDelete;
use App\Actions\ProjectBlueprintStore;
use App\Actions\ProjectBlueprintUpdate;
use App\Commands\BlueprintComponentStoreCommand;
use App\Commands\ProjectBlueprintDeleteCommand;
use App\Commands\ProjectBlueprintStoreCommand;
use App\Commands\ProjectBlueprintUpdateCommand;
use App\Exceptions\ControllerException;
use App\Http\Controllers\Controller;
use App\Http\Requests\ProjectBlueprintIndexRequest;
use App\Http\Requests\ProjectBlueprintStoreRequest;
use App\Http\Requests\ProjectBlueprintUpdateRequest;
use App\Models\Project;
use App\Models\ProjectBlueprint;
use App\Queries\ProjectBlueprintReadQuery;
use App\Repositories\BlueprintComponentRepository;
use App\Repositories\ProjectBlueprintRepository;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Gate;
use Symfony\Component\HttpFoundation\Response;
use Tripsy\ApiWrapper\ApiWrapper;

class ApiProjectBlueprintController extends Controller
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
        ProjectBlueprintIndexRequest $request,
        Project $project,
        ProjectBlueprintReadQuery $query
    ): JsonResponse {
        Gate::authorize('index', [ProjectBlueprint::class, $project]);

        $validated = $request->validated();

        $results = $query
            ->filterByProjectId($project->id)
            ->filterByName('%'.$validated['filter']['name'].'%', 'LIKE')
            ->filterByDescription('%'.$validated['filter']['description'].'%', 'LIKE')
            ->filterByStatus($validated['filter']['status'])
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
        ProjectBlueprintStoreRequest $request,
        Project $project,
        ProjectBlueprintReadQuery $query
    ): JsonResponse {
        Gate::authorize('create', [ProjectBlueprint::class, $project]);

        $validated = $request->validated();

        try {
            DB::beginTransaction();

            $commandProjectBlueprint = new ProjectBlueprintStoreCommand(
                $project->id,
                $validated['name'],
                $validated['description'],
                $validated['status'],
            );

            ProjectBlueprintStore::run($commandProjectBlueprint);

            $projectBlueprint = $query
                ->filterByProjectId($commandProjectBlueprint->getProjectId())
                ->filterByName($commandProjectBlueprint->getName())
                ->firstOrFail();

            foreach ($validated['components'] as $blueprintComponent) {
                $commandBlueprintComponent = new BlueprintComponentStoreCommand(
                    $projectBlueprint->id,
                    $blueprintComponent['name'],
                    $blueprintComponent['description'],
                    $blueprintComponent['info'],
                    $blueprintComponent['component_type'],
                    $blueprintComponent['component_format'],
                    $blueprintComponent['type_options'] ?? [], //is not a required field
                    $blueprintComponent['is_required'],
                    $blueprintComponent['status'] ?? '',
                );

                BlueprintComponentStore::run($commandBlueprintComponent);
            }

            DB::commit();
        } catch (ModelNotFoundException) {
            DB::rollBack();

            throw new ControllerException(
                __('message.project_blueprint.store_fail'),
                Response::HTTP_INTERNAL_SERVER_ERROR
            );
        }

        $this->apiWrapper->success(true);
        $this->apiWrapper->message(__('message.success'));
        $this->apiWrapper->data(array_merge(
            [
                'id' => $projectBlueprint->id,
            ],
            $commandProjectBlueprint->attributes(),
            [
                'components' => $validated['components'],
            ],
        ));

        return response()->json($this->apiWrapper->resultArray(), Response::HTTP_CREATED);
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
            $result = $query
                ->filterById($projectBlueprint->id)
                ->withCreatedBy()
                ->withUpdatedBy()
                ->withComponents()
                ->first();

            if ($result) {
                $result->components->each(function ($component) {
                    $component->makeHidden('project_blueprint_id');
                });
            }

            return $result;
        });

        $this->apiWrapper->success(true);
        $this->apiWrapper->message(__('message.success'));
        $this->apiWrapper->pushMeta('isCached', $repository->isCached());
        $this->apiWrapper->data($data);

        return response()->json($this->apiWrapper->resultArray(), Response::HTTP_OK);
    }

    /**
     * Update the specified resource in storage.
     * @throws ControllerException
     */
    public function update(
        ProjectBlueprintUpdateRequest $request,
        Project $project,
        ProjectBlueprint $projectBlueprint,
        BlueprintComponentRepository $blueprintComponentRepository
    ): JsonResponse {
        Gate::authorize('update', [$projectBlueprint, $project]);

        $validated = $request->validated();

        try {
            DB::beginTransaction();

            $command = new ProjectBlueprintUpdateCommand(
                $projectBlueprint->id,
                $validated['name'],
                $validated['description'],
            );

            ProjectBlueprintUpdate::run($command);

            if (empty($validated['components']) === false) {
                $blueprintComponentRepository->onUpDateHandleComponents(
                    $projectBlueprint->id,
                    $validated['components']
                );
            }

            DB::commit();
        } catch (ModelNotFoundException) {
            DB::rollBack();

            throw new ControllerException(
                __('message.project_blueprint.store_fail'),
                Response::HTTP_INTERNAL_SERVER_ERROR
            );
        }

        $this->apiWrapper->success(true);
        $this->apiWrapper->message(__('message.success'));
        $this->apiWrapper->data(array_merge(
            $command->attributes(),
            [
                'components' => $validated['components'],
            ],
        ));

        return response()->json($this->apiWrapper->resultArray(), Response::HTTP_OK);
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

        $this->apiWrapper->success(true);
        $this->apiWrapper->message(__('message.success'));
        $this->apiWrapper->data($command->attributes());

        return response()->json($this->apiWrapper->resultArray(), Response::HTTP_ACCEPTED);
    }
}
