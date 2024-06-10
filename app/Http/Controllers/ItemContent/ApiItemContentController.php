<?php

declare(strict_types=1);

namespace App\Http\Controllers\ItemContent;

use App\Actions\ItemContentDelete;
use App\Actions\ItemContentStore;
use App\Actions\ItemContentUpdate;
use App\Commands\ItemContentDeleteCommand;
use App\Commands\ItemContentStoreCommand;
use App\Commands\ItemContentUpdateCommand;
use App\Exceptions\ControllerException;
use App\Http\Controllers\Controller;
use App\Http\Requests\ItemContentIndexRequest;
use App\Http\Requests\ItemContentStoreRequest;
use App\Http\Requests\ItemContentUpdateRequest;
use App\Models\Item;
use App\Models\ItemContent;
use App\Queries\ItemContentQuery;
use App\Repositories\ItemContentRepository;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Gate;
use Symfony\Component\HttpFoundation\Response;
use Tripsy\ApiWrapper\ApiWrapper;

extra App\Http\ItemContent\ApiItemContentStatusController::nested;
extra App\Events\ItemContentActivated;
extra App\Events\ItemContentCache;
extra App\Events\ItemContentCreated;
extra App\Events\ItemContentDeleting;
extra App\Events\ItemContentUpdated;
extra App\Listeners\ItemContentSubscriber;
extra App\Observers\ItemContentObserver;
extra App\Policies\ItemContentPolicy;

class ApiItemContentController extends Controller
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
        ItemContentIndexRequest $request,
        Item $item,
        ItemContentQuery $query
    ): JsonResponse {
        Gate::authorize('index', [ItemContent::class, $item]);

        $validated = $request->validated();

        $results = $query
            ->filterByItemId($item->id)
            ->filterByName('%'.$validated['filter']['name'].'%', 'LIKE')
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
        ItemContentStoreRequest $request,
        Item $item,
        ItemContentQuery $query
    ): JsonResponse {
        Gate::authorize('create', [ItemContent::class, $item]);

        $validated = $request->validated();

        try {
            $commandItemContent = new ItemContentStoreCommand(
                $item->id,
                $validated['name'],
                $validated['description'],
                $validated['status'],
            );

            ItemContentStore::run($commandItemContent);

            $itemContent = $query
                ->filterByItemId($commandItemContent->getItemId())
                ->filterByName($commandItemContent->getName())
                ->firstOrFail();
        } catch (ModelNotFoundException) {
            throw new ControllerException(
                __('message.??.store_fail'),
                Response::HTTP_INTERNAL_SERVER_ERROR
            );
        }

        $this->apiWrapper->success(true);
        $this->apiWrapper->message(__('message.success'));
        $this->apiWrapper->data(array_merge(
            [
                'id' => $itemContent->id,
            ],
            $commandItemContent->attributes()
        ));

        return response()->json($this->apiWrapper->resultArray(), Response::HTTP_CREATED);
    }

    /**
     * Display the specified resource.
     */
    public function show(
        Item                  $item,
        ItemContent           $itemContent,
        ItemContentQuery      $query,
        ItemContentRepository $repository
    ): JsonResponse {
        Gate::authorize('view', [ItemContent::class, $item]);

        $data = $repository->getViewCache($itemContent->id, function () use ($query, $itemContent) {
            return $query
                ->filterById($itemContent->id)
                ->withCreatedBy()
                ->withUpdatedBy()
                ->first();
            }
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
        ItemContentUpdateRequest $request,
        Item $item,
        ItemContent $itemContent
    ): JsonResponse {
        Gate::authorize('update', [$itemContent, $item]);

        $validated = $request->validated();

        try {
            $command = new ItemContentUpdateCommand(
                $itemContent->id,
                $validated['name'],
                $validated['description'],
            );

            ItemContentUpdate::run($command);
        } catch (ModelNotFoundException) {
            throw new ControllerException(
                __('message.???.store_fail'),
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
    public function destroy(Item $item, ItemContent $itemContent): JsonResponse
    {
        Gate::authorize('delete', [ItemContent::class, $item]);

        $command = new ItemContentDeleteCommand(
            $itemContent->id
        );

        ItemContentDelete::run($command);

        $this->apiWrapper->success(true);
        $this->apiWrapper->message(__('message.success'));
        $this->apiWrapper->data($command->attributes());

        return response()->json($this->apiWrapper->resultArray(), Response::HTTP_ACCEPTED);
    }
}
