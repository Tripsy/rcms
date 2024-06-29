<?php

declare(strict_types=1);

namespace App\Http\Controllers\Item;

use App\Actions\ItemStatusUpdate;
use App\Commands\ItemStatusUpdateCommand;
use App\Enums\ItemStatus;
use App\Http\Controllers\Controller;
use App\Models\ProjectBlueprint;
use App\Models\Item;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Gate;
use Symfony\Component\HttpFoundation\Response;

class ApiItemStatusController extends Controller
{
    /**
     * Handle the incoming request.
     */
    public function __invoke(
        ProjectBlueprint $projectBlueprint,
        Item $item,
        ItemStatus $status
    ): JsonResponse {
        Gate::authorize('update', [Item::class, $projectBlueprint->project->first()]);

        $command = new ItemStatusUpdateCommand(
            $item->id,
            $projectBlueprint->id,
            $status->value
        );

        ItemStatusUpdate::run($command);

        return response()->json([
            'success' => true,
            'message' => __('message.success'),
            'data' => $command->attributes(),
        ], Response::HTTP_OK);
    }
}
