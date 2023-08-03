<?php

namespace App\Http\Controllers\Item;

use App\Bus\CommandBus;
use App\Commands\ItemDataStoreCommand;
use App\Commands\ItemStoreCommand;
use App\Commands\ItemUpdateCommand;
use App\Enums\ItemStatus;
use App\Http\Controllers\Controller;
use App\Http\Requests\ItemStoreRequest;
use App\Http\Requests\ItemUpdateRequest;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Bus;
use Illuminate\Support\Str;
use Symfony\Component\HttpFoundation\Response;

class ApiItemController extends Controller
{
    protected CommandBus $commandBus;

    public function __construct(CommandBus $commandBus)
    {
        $this->commandBus = $commandBus;
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

        $uuid = Str::orderedUuid();

        $commandItem = new ItemStoreCommand(
            $uuid,
            $validated['account_id'],
            $validated['description'],
            empty($validated['status']) === false ? ItemStatus::from($validated['status']) : ItemStatus::DRAFT
        );

        Bus::dispatchSync($commandItem);

//        $this->commandBus->execute($commandItem);

        foreach($validated['data'] as $itemData) {
            $commandItemData = new ItemDataStoreCommand(
                $uuid,
                $itemData['label'],
                $itemData['content'],
            );

            $this->commandBus->execute($commandItemData);
        }

        return response()->json([
            'success' => true,
            'message' => __('message.success'),
            'data' => [
                'uuid' => $uuid,
                'description' => $validated['description'],
                'data' => $validated['data'],
            ]
        ], Response::HTTP_CREATED);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(ItemUpdateRequest $request, string $uuid)
    {
        $validated = $request->validated();

        $commandItem = new ItemUpdateCommand(
            $validated['description']
        );

        $this->commandBus->execute($commandItem);

//        foreach($validated['data'] as $itemData) {
//            $commandItemData = new ItemDataStoreCommand(
//                $uuid,
//                $itemData['label'],
//                $itemData['content'],
//            );
//
//            $this->commandBus->execute($commandItemData);
//        }

        return response()->json([
            'success' => true,
            'message' => __('message.success'),
            'data' => [
                'uuid' => $uuid,
                'description' => $validated['description'],
                'data' => $validated['data'],
            ]
        ], Response::HTTP_CREATED);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
