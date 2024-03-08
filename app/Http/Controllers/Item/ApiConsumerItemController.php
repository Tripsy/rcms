<?php

namespace App\Http\Controllers\Item;

use App\Actions\ItemDataStore;
use App\Actions\ItemStore;
use App\Actions\ItemUpdate;
use App\Commands\ItemDataStoreCommand;
use App\Commands\BlueprintComponentStoreCommand;
use App\Commands\ItemUpdateCommand;
use App\Enums\ProjectItemStatus;
use App\Enums\CommonStatus;
use App\Http\Controllers\Controller;
use App\Http\Requests\ItemStoreRequest;
use App\Http\Requests\ItemUpdateRequest;
use App\Models\ItemContent;
use App\Models\ProjectItem;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Str;
use Symfony\Component\HttpFoundation\Response;
use Tripsy\ApiWrapper\ApiWrapper;

class ApiConsumerItemController extends Controller
{
    private ApiWrapper $apiWrapper;

    public function __construct(ApiWrapper $apiWrapper)
    {
        $this->apiWrapper = $apiWrapper;
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //https://laravel.com/docs/10.x/eloquent-resources
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(ItemStoreRequest $request): JsonResponse
    {
        $validated = $request->validated();

        $commandItem = new BlueprintComponentStoreCommand(
            Str::orderedUuid(),
            $validated['account_id'],
            $validated['description'],
            empty($validated['status']) === false ? CommonStatus::from($validated['status']) : CommonStatus::DRAFT
        );

        ItemStore::run($commandItem);

        foreach ($validated['data'] as $itemData) {
            $commandItemData = new ItemDataStoreCommand(
                $commandItem->getUuid(),
                $itemData['label'],
                $itemData['content'],
            );

            ItemDataStore::run($commandItemData);
        }

        $this->apiWrapper->success(true);
        $this->apiWrapper->message(__('message.success'));
        $this->apiWrapper->data([
            'uuid' => $commandItem->getUuid(),
            'description' => $commandItem->getDescription(),
            'data' => $validated['data'],
        ]);

        return response()->json($this->apiWrapper->resultArray(), Response::HTTP_CREATED);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $uuid)
    {
        $item = ProjectItem::query()
            ->uuid($uuid)
            ->where('account_id', $account_id)
            ->firstOrFail();

        $itemData = ItemContent::query()
            ->uuid($uuid)
            ->isActive()
            ->get(['label', 'content'])
            ->toArray();

        return response()->json([
            'success' => true,
            'message' => __('message.success'),
            'data' => [
                'uuid' => $uuid,
                'description' => $item->description,
                'status' => $item->status,
                'created_at' => $item->created_at,
                'created_by' => $item->created_by,
                'updated_at' => $item->updated_at,
                'updated_by' => $item->updated_by,
                'data' => $itemData,
            ],
        ], Response::HTTP_CREATED);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(ItemUpdateRequest $request, string $uuid): JsonResponse
    {
        $validated = $request->validated();

        $commandItem = new ItemUpdateCommand(
            $uuid,
            $validated['description']
        );

        ItemUpdate::run($commandItem);

        foreach ($validated['data'] as $itemData) {
            $commandItemData = new ItemDataStoreCommand(
                $commandItem->getUuid(),
                $itemData['label'],
                $itemData['content'],
            );

            ItemDataStore::run($commandItemData);
        }

        return response()->json([
            'success' => true,
            'message' => __('message.success'),
            'data' => [
                'uuid' => $uuid,
                'description' => $commandItem->getDescription(),
                'data' => $validated['data'],
            ],
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
