<?php

declare(strict_types=1);

namespace App\Http\Controllers\ProjectBlueprint;

use App\Actions\ProjectBlueprintStatusUpdate;
use App\Commands\ProjectBlueprintStatusUpdateCommand;
use App\Enums\CommonStatus;
use App\Http\Controllers\Controller;
use App\Models\Project;
use App\Models\ProjectBlueprint;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Gate;
use Symfony\Component\HttpFoundation\Response;

class ApiProjectBlueprintStatusController extends Controller
{
    /**
     * Handle the incoming request.
     */
    public function __invoke(
        Project $project,
        ProjectBlueprint $projectBlueprint,
        CommonStatus $status
    ): JsonResponse {
        Gate::authorize('update', [$projectBlueprint, $project]);

        $command = new ProjectBlueprintStatusUpdateCommand(
            $projectBlueprint->id,
            $project->id,
            $status->value
        );

        ProjectBlueprintStatusUpdate::run($command);

        return response()->json([
            'success' => true,
            'message' => __('message.success'),
            'data' => $command->attributes(),
        ], Response::HTTP_OK);
    }
}
