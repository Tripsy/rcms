<?php

declare(strict_types=1);

namespace App\Http\Controllers\BlueprintComponent;

use App\Actions\BlueprintComponentStatusUpdate;
use App\Commands\BlueprintComponentStatusUpdateCommand;
use App\Enums\CommonStatus;
use App\Http\Controllers\Controller;
use App\Models\ProjectBlueprint;
use App\Models\BlueprintComponent;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Gate;
use Symfony\Component\HttpFoundation\Response;

class ApiBlueprintComponentStatusController extends Controller
{
    /**
     * Handle the incoming request.
     */
    public function __invoke(
        ProjectBlueprint $projectBlueprint,
        BlueprintComponent $blueprintComponent,
        CommonStatus $status
    ): JsonResponse {
        Gate::authorize('update', [BlueprintComponent::class, $projectBlueprint->project->first()]);

        $command = new BlueprintComponentStatusUpdateCommand(
            $blueprintComponent->id,
            $projectBlueprint->id,
            $status->value
        );

        BlueprintComponentStatusUpdate::run($command);

        return response()->json([
            'success' => true,
            'message' => __('message.success'),
            'data' => $command->attributes(),
        ], Response::HTTP_OK);
    }
}
