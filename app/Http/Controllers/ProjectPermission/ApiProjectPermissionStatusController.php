<?php

declare(strict_types=1);

namespace App\Http\Controllers\ProjectPermission;

use App\Actions\ProjectPermissionStatusUpdate;
use App\Commands\ProjectPermissionStatusUpdateCommand;
use App\Enums\CommonStatus;
use App\Http\Controllers\Controller;
use App\Models\Project;
use App\Models\ProjectPermission;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Gate;
use Symfony\Component\HttpFoundation\Response;

class ApiProjectPermissionStatusController extends Controller
{
    /**
     * Handle the incoming request.
     */
    public function __invoke(
        Project $project,
        ProjectPermission $projectPermission,
        CommonStatus $status
    ): JsonResponse {
        Gate::authorize('update', [$projectPermission, $project]);

        $command = new ProjectPermissionStatusUpdateCommand(
            $projectPermission->id,
            $status->value
        );

        ProjectPermissionStatusUpdate::run($command);

        return response()->json([
            'success' => true,
            'message' => __('message.success'),
            'data' => $command->attributes(),
        ], Response::HTTP_OK);
    }
}
