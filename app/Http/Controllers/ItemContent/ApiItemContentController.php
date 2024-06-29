<?php

declare(strict_types=1);

namespace App\Http\Controllers\ItemContent;

use App\Actions\ItemContentDelete;
use App\Actions\ItemContentStore;
use App\Commands\ItemContentDeleteCommand;
use App\Commands\ItemContentStoreCommand;
use App\Exceptions\ControllerException;
use App\Http\Controllers\Controller;
use App\Http\Requests\ItemContentIndexRequest;
use App\Http\Requests\ItemContentStoreRequest;
use App\Models\Item;
use App\Models\ItemContent;
use App\Queries\ItemContentQuery;
use App\Repositories\ItemContentRepository;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Gate;
use Symfony\Component\HttpFoundation\Response;
use Tripsy\ApiWrapper\ApiWrapper;

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
        Gate::authorize('index', [ItemContent::class, $item->blueprint->project->first()]);

        $validated = $request->validated();

        $results = $query
            ->filterByItemId($item->id)
            ->filterByBlueprintComponentId($validated['filter']['blueprint_component_id'])
            ->filterBy('is_active', $validated['filter']['is_active'])
            ->withCreatedBy()
            ->withUpdatedBy()
            ->get($validated['page'], $validated['limit'])
            ->makeHidden(['user_id']);

        $this->apiWrapper->success(true);
        $this->apiWrapper->message(__('message.success'));
        $this->apiWrapper->data([
            'results' => $results,
            'filter' => array_filter($validated['filter']),
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
        ItemContentRepository $itemContentRepository
    ): JsonResponse {
        Gate::authorize('index', [ItemContent::class, $item->blueprint->project->first()]);

        $validated = $request->validated();

        try {
            $itemContent = $itemContentRepository->getActiveContent(
                $item->id,
                $validated['blueprint_component_id']
            );

            if (empty($itemContent) === false && $itemContent['content'] == $validated['content']) {
                $message = __('message.item_content.no_change');
                $data = [
                    'id' => $itemContent->id,
                    'item_id' => $itemContent->item_id,
                    'blueprint_component_id' => $itemContent->blueprint_component_id,
                    'content' => $itemContent->content,
                ];
            } else {
                $command = new ItemContentStoreCommand(
                    $item->id,
                    $validated['blueprint_component_id'],
                    $validated['content']
                );

                ItemContentStore::run($command);

                $itemContent = $itemContentRepository->getActiveContent(
                    $command->getItemId(),
                    $command->getBlueprintComponentId()
                );

                $message = __('message.success');
                $data = array_merge(
                    [
                        'id' => $itemContent->id,
                    ],
                    $command->attributes(),
                );
            }
        } catch (ModelNotFoundException) {
            throw new ControllerException(
                __('message.item_content.store_fail'),
                Response::HTTP_INTERNAL_SERVER_ERROR
            );
        }

        $this->apiWrapper->success(true);
        $this->apiWrapper->message($message);
        $this->apiWrapper->data($data);

        return response()->json($this->apiWrapper->resultArray(), Response::HTTP_CREATED);
    }

    /**
     * Display the specified resource.
     */
    public function show(
        Item $item,
        ItemContent $itemContent,
        ItemContentQuery $query,
        ItemContentRepository $repository
    ): JsonResponse {
        Gate::authorize('index', [ItemContent::class, $item->blueprint->project->first()]);

        $data = $repository->getViewCache($itemContent->id, function () use ($query, $item, $itemContent) {
            return $query
                ->filterById($itemContent->id)
                ->filterByItemId($item->id)
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
     * Remove the specified resource from storage.
     */
    public function destroy(Item $item, ItemContent $itemContent): JsonResponse
    {
        Gate::authorize('index', [ItemContent::class, $item->blueprint->project->first()]);

        $command = new ItemContentDeleteCommand(
            $itemContent->id,
            $item->id
        );

        ItemContentDelete::run($command);

        $this->apiWrapper->success(true);
        $this->apiWrapper->message(__('message.success'));
        $this->apiWrapper->data($command->attributes());

        return response()->json($this->apiWrapper->resultArray(), Response::HTTP_ACCEPTED);
    }
}
