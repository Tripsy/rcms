<?php

namespace App\Http\Controllers\Item;

use App\Commands\ItemDataStoreCommand;
use App\Commands\ItemStoreCommand;
use App\Commands\ItemUpdateCommand;
use App\Enums\ItemStatus;
use App\Http\Controllers\Controller;
use App\Http\Requests\ItemStoreRequest;
use App\Http\Requests\ItemUpdateRequest;
use App\Interfaces\ItemRepositoryInterface;
use App\Jobs\ItemDataStore;
use App\Jobs\ItemStore;
use App\Jobs\ItemUpdate;
use App\Models\Item;
use App\Models\ItemData;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Str;
use Symfony\Component\HttpFoundation\Response;

//https://www.restapitutorial.com/lessons/httpmethods.html //TODO
//https://www.geeksforgeeks.org/restful-routes-in-node-js/ //TODO

class ApiConsumerItemController extends Controller
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

        ItemStore::dispatchSync($commandItem);

        foreach($validated['data'] as $itemData) {
            $commandItemData = new ItemDataStoreCommand(
                $commandItem->getUuid(),
                $itemData['label'],
                $itemData['content'],
            );

            ItemDataStore::dispatchSync($commandItemData);
        }

        return response()->json([
            'success' => true,
            'message' => __('message.success'),
            'data' => [
                'uuid' => $commandItem->getUuid(),
                'description' => $validated['description'],
                'data' => $validated['data'],
            ]
        ], Response::HTTP_CREATED);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $uuid)
    {
        $item = Item::query()
            ->uuid($uuid)
            ->where('account_id', $account_id)
            ->firstOrFail();

        $itemData = ItemData::query()
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
                'data' => $itemData
            ]
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

        ItemUpdate::dispatchSync($commandItem);

        foreach($validated['data'] as $itemData) {
            $commandItemData = new ItemDataStoreCommand(
                $commandItem->getUuid(),
                $itemData['label'],
                $itemData['content'],
            );

            ItemDataStore::dispatchSync($commandItemData);
        }

        return response()->json([
            'success' => true,
            'message' => __('message.success'),
            'data' => [
                'uuid' => $uuid,
                'description' => $validated['description'],
                'data' => $validated['data'],
            ]
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
