<?php

declare(strict_types=1);

namespace App\Http\Controllers\Item;

use App\Actions\ItemContentStore;
use App\Actions\ItemDelete;
use App\Actions\ItemStore;
use App\Actions\ItemUpdate;
use App\Commands\ItemContentStoreCommand;
use App\Commands\ItemDeleteCommand;
use App\Commands\ItemStoreCommand;
use App\Commands\ItemUpdateCommand;
use App\Exceptions\ControllerException;
use App\Http\Controllers\Controller;
use App\Http\Requests\ItemIndexRequest;
use App\Http\Requests\ItemStoreRequest;
use App\Http\Requests\ItemUpdateRequest;
use App\Models\Item;
use App\Models\ProjectBlueprint;
use App\Queries\ItemQuery;
use App\Repositories\ItemRepository;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Gate;
use Symfony\Component\HttpFoundation\Response;
use Tripsy\ApiWrapper\ApiWrapper;

class ApiItemController extends Controller
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
        ItemIndexRequest $request,
        ProjectBlueprint $projectBlueprint,
        ItemQuery $query
    ): JsonResponse {
        Gate::authorize('index', [Item::class, $projectBlueprint->project()->first()]);

        $validated = $request->validated();

        $results = $query
            ->filterByProjectBlueprintId($projectBlueprint->id)
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
        ItemStoreRequest $request,
        ProjectBlueprint $projectBlueprint,
        ItemQuery $query
    ): JsonResponse {
        Gate::authorize('create', [Item::class, $projectBlueprint->project()->first()]);

        $validated = $request->validated();

        try {
            DB::beginTransaction();

            $commandItem = new ItemStoreCommand(
                $projectBlueprint->id,
                $validated['description'],
                $validated['status'],
            );

            ItemStore::run($commandItem);

            $item = $query
                ->filterByUuid($commandItem->getUuid())
                ->firstOrFail();

            foreach ($validated['contents'] as $itemContent) {
                $commandBlueprintComponent = new ItemContentStoreCommand(
                    $commandItem->id,
                    $itemContent['blueprint_component_id'],
                    $itemContent['content']
                );

                ItemContentStore::run($commandBlueprintComponent);
            }

            DB::commit();
        } catch (ModelNotFoundException) {
            DB::rollBack();

            throw new ControllerException(
                __('message.item.store_fail'),
                Response::HTTP_INTERNAL_SERVER_ERROR
            );
        }

        $this->apiWrapper->success(true);
        $this->apiWrapper->message(__('message.success'));
        $this->apiWrapper->data(array_merge(
            [
                'id' => $item->id,
            ],
            $commandItem->attributes(),
            [
                //'contents' => $validated['contents'],
            ],
        ));

        return response()->json($this->apiWrapper->resultArray(), Response::HTTP_CREATED);
    }

    /**
     * Display the specified resource.
     */
    public function show(
        ProjectBlueprint $projectBlueprint,
        Item $item,
        ItemQuery $query,
        ItemRepository $repository
    ): JsonResponse {
        Gate::authorize('view', [Item::class, $projectBlueprint->project()->first()]);

        $data = $repository->getViewCache($item->id, function () use ($query, $projectBlueprint, $item) {
            return $query
                ->filterById($item->id)
                ->filterByProjectBlueprintId($item->id)
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
        ItemUpdateRequest $request,
        ProjectBlueprint $projectBlueprint,
        Item $item
    ): JsonResponse {
        Gate::authorize('update', [Item::class, $projectBlueprint->project()->first()]);

        $validated = $request->validated();

        try {
            $command = new ItemUpdateCommand(
                $item->id,
                $projectBlueprint->id,
                $validated['description']
            );

            ItemUpdate::run($command);
        } catch (ModelNotFoundException) {
            throw new ControllerException(
                __('message.item.store_fail'),
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
    public function destroy(ProjectBlueprint $projectBlueprint, Item $item): JsonResponse
    {
        Gate::authorize('delete', [Item::class, $projectBlueprint->project()->first()]);

        $command = new ItemDeleteCommand(
            $item->id,
            $projectBlueprint->id
        );

        ItemDelete::run($command);

        $this->apiWrapper->success(true);
        $this->apiWrapper->message(__('message.success'));
        $this->apiWrapper->data($command->attributes());

        return response()->json($this->apiWrapper->resultArray(), Response::HTTP_ACCEPTED);
    }
}
