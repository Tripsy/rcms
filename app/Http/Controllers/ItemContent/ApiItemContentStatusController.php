<?php

declare(strict_types=1);

namespace App\Http\Controllers\ItemContent;

use App\Actions\ItemContentStatusUpdate;
use App\Commands\ItemContentStatusUpdateCommand;
use App\Enums\CommonStatus;
use App\Http\Controllers\Controller;
use App\Models\Item;
use App\Models\ItemContent;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Gate;
use Symfony\Component\HttpFoundation\Response;

class ApiItemContentStatusController extends Controller
{
    /**
     * Handle the incoming request.
     */
    public function __invoke(
        Item $item,
        ItemContent $itemContent,
        CommonStatus $status
    ): JsonResponse {
        Gate::authorize('update', [$itemContent, $item]);

        $command = new ItemContentStatusUpdateCommand(
            $itemContent->id,
            $status->value
        );

        ItemContentStatusUpdate::run($command);

        return response()->json([
            'success' => true,
            'message' => __('message.success'),
            'data' => $command->attributes(),
        ], Response::HTTP_OK);
    }
}
