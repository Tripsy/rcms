<?php

declare(strict_types=1);

namespace App\Http\Controllers\Project;

use App\Actions\ProjectStatusUpdate;
use App\Commands\ProjectStatusUpdateCommand;
use App\Enums\CommonStatus;
use App\Http\Controllers\Controller;
use App\Models\Project;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Gate;
use Symfony\Component\HttpFoundation\Response;

class ApiProjectStatusController extends Controller
{
    /**
     * Handle the incoming request.
     */
    public function __invoke(Project $project, CommonStatus $status): JsonResponse
    {
        Gate::authorize('update', $project);

        $command = new ProjectStatusUpdateCommand(
            $project->id,
            $status->value
        );

        ProjectStatusUpdate::run($command);

        return response()->json([
            'success' => true,
            'message' => __('message.success'),
            'data' => $command->attributes(),
        ], Response::HTTP_OK);
    }
}
