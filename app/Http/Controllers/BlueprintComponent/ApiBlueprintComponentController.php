<?php

declare(strict_types=1);

namespace App\Http\Controllers\BlueprintComponent;

use App\Actions\BlueprintComponentDelete;
use App\Actions\BlueprintComponentStore;
use App\Actions\BlueprintComponentUpdate;
use App\Commands\BlueprintComponentDeleteCommand;
use App\Commands\BlueprintComponentStoreCommand;
use App\Commands\BlueprintComponentUpdateCommand;
use App\Exceptions\ControllerException;
use App\Http\Controllers\Controller;
use App\Http\Requests\BlueprintComponentIndexRequest;
use App\Http\Requests\BlueprintComponentStoreRequest;
use App\Http\Requests\BlueprintComponentUpdateRequest;
use App\Models\BlueprintComponent;
use App\Models\ProjectBlueprint;
use App\Queries\BlueprintComponentReadQuery;
use App\Repositories\BlueprintComponentRepository;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Gate;
use Symfony\Component\HttpFoundation\Response;
use Tripsy\ApiWrapper\ApiWrapper;

class ApiBlueprintComponentController extends Controller
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
        BlueprintComponentIndexRequest $request,
        ProjectBlueprint $projectBlueprint,
        BlueprintComponentReadQuery $query
    ): JsonResponse {
        Gate::authorize('index', [BlueprintComponent::class, $projectBlueprint->project()->first()]);

        $validated = $request->validated();

        $results = $query
            ->filterByProjectBlueprintId($projectBlueprint->id)
            ->filterByStatus($validated['filter']['status'])
            ->filterByComponentType($validated['filter']['component_type'])
            ->filterByComponentFormat($validated['filter']['component_format'])
            ->isRequired($validated['filter']['is_required'])
            ->filterByName('%'.$validated['filter']['name'].'%', 'LIKE')
            ->filterByDescription('%'.$validated['filter']['description'].'%', 'LIKE')
            ->filterByInfo('%'.$validated['filter']['info'].'%', 'LIKE')
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
        BlueprintComponentStoreRequest $request,
        ProjectBlueprint $projectBlueprint,
        BlueprintComponentReadQuery $query
    ): JsonResponse {
        Gate::authorize('create', [BlueprintComponent::class, $projectBlueprint->project()->first()]);

        $validated = $request->validated();

        try {
            $commandBlueprintComponent = new BlueprintComponentStoreCommand(
                $projectBlueprint->id,
                $validated['name'],
                $validated['description'],
                $validated['info'],
                $validated['component_type'],
                $validated['component_format'],
                $validated['type_options'] ?? [], //is not a required field
                $validated['is_required'],
                $validated['status'],
            );

            BlueprintComponentStore::run($commandBlueprintComponent);

            $blueprintComponent = $query
                ->filterByProjectBlueprintId($commandBlueprintComponent->getProjectBlueprintId())
                ->filterByName($commandBlueprintComponent->getName())
                ->firstOrFail();
        } catch (ModelNotFoundException) {
            throw new ControllerException(
                __('message.blueprint_component.store_fail'),
                Response::HTTP_INTERNAL_SERVER_ERROR
            );
        }

        $this->apiWrapper->success(true);
        $this->apiWrapper->message(__('message.success'));
        $this->apiWrapper->data(array_merge(
            [
                'id' => $blueprintComponent->id,
            ],
            $commandBlueprintComponent->attributes()
        ));

        return response()->json($this->apiWrapper->resultArray(), Response::HTTP_CREATED);
    }

    /**
     * Display the specified resource.
     */
    public function show(
        ProjectBlueprint $projectBlueprint,
        BlueprintComponent $blueprintComponent,
        BlueprintComponentReadQuery $query,
        BlueprintComponentRepository $repository
    ): JsonResponse {
        Gate::authorize('view', [BlueprintComponent::class, $projectBlueprint->project()->first()]);

        $data = $repository->getViewCache($blueprintComponent->id, function () use ($query, $blueprintComponent) {
            return $query
                ->filterById($blueprintComponent->id)
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
     *
     * @throws ControllerException
     */
    public function update(
        BlueprintComponentUpdateRequest $request,
        ProjectBlueprint $projectBlueprint,
        BlueprintComponent $blueprintComponent
    ): JsonResponse {
        Gate::authorize('update', [BlueprintComponent::class, $projectBlueprint->project()->first()]);

        $validated = $request->validated();

        try {
            $command = new BlueprintComponentUpdateCommand(
                $blueprintComponent->id,
                $validated['name'],
                $validated['description'],
                $validated['info'],
                $validated['component_type'],
                $validated['component_format'],
                $validated['type_options'] ?? [],
                $validated['is_required']
            );

            BlueprintComponentUpdate::run($command);
        } catch (ModelNotFoundException) {
            throw new ControllerException(
                __('message.blueprint_component.store_fail'),
                Response::HTTP_INTERNAL_SERVER_ERROR
            );
        }

        $this->apiWrapper->success(true);
        $this->apiWrapper->message(__('message.success'));
        $this->apiWrapper->data($command->attributes());

        return response()->json($this->apiWrapper->resultArray(), Response::HTTP_OK);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(ProjectBlueprint $projectBlueprint, BlueprintComponent $blueprintComponent): JsonResponse
    {
        Gate::authorize('delete', [BlueprintComponent::class, $projectBlueprint->project()->first()]);

        $command = new BlueprintComponentDeleteCommand(
            $blueprintComponent->id
        );

        BlueprintComponentDelete::run($command);

        $this->apiWrapper->success(true);
        $this->apiWrapper->message(__('message.success'));
        $this->apiWrapper->data($command->attributes());

        return response()->json($this->apiWrapper->resultArray(), Response::HTTP_ACCEPTED);
    }
}
