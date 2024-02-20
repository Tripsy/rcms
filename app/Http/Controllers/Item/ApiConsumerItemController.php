<?php

namespace App\Http\Controllers\Item;

use App\Actions\ItemDataStore;
use App\Actions\ItemStore;
use App\Actions\ItemUpdate;
use App\Commands\ItemDataStoreCommand;
use App\Commands\ItemStoreCommand;
use App\Commands\ItemUpdateCommand;
use App\Enums\ItemStatus;
use App\Http\Controllers\Controller;
use App\Http\Requests\ItemStoreRequest;
use App\Http\Requests\ItemUpdateRequest;
use App\Models\ProjectItem;
use App\Models\ItemContent;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Str;
use Symfony\Component\HttpFoundation\Response;
use Tripsy\ApiResponse\ApiResponse;

class ApiConsumerItemController extends Controller
{
    private ApiResponse $apiResponse;

    public function __construct(ApiResponse $apiResponse)
    {
        $this->apiResponse = $apiResponse;
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

        $commandItem = new ItemStoreCommand(
            Str::orderedUuid(),
            $validated['account_id'],
            $validated['description'],
            empty($validated['status']) === false ? ItemStatus::from($validated['status']) : ItemStatus::DRAFT
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

        $this->apiResponse->success(true);
        $this->apiResponse->message(__('message.success'));
        $this->apiResponse->data([
            'uuid' => $commandItem->getUuid(),
            'description' => $commandItem->getDescription(),
            'data' => $validated['data'],
        ]);

        return response()->json($this->apiResponse->resultArray(), Response::HTTP_CREATED);
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
