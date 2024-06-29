<?php

declare(strict_types=1);

namespace App\Http\Controllers\Tags;

use App\Actions\TagsStatusUpdate;
use App\Commands\TagsStatusUpdateCommand;
use App\Enums\CommonStatus;
use App\Http\Controllers\Controller;
use App\Models\Project;
use App\Models\Tag;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Gate;
use Symfony\Component\HttpFoundation\Response;

class ApiTagsStatusController extends Controller
{
    /**
     * Handle the incoming request.
     */
    public function __invoke(
        Project      $project,
        Tag          $tags,
        CommonStatus $status
    ): JsonResponse {
        Gate::authorize('update', [Tag::class, $project]);

        $command = new TagsStatusUpdateCommand(
            $tags->id,
            $project->id,
            $status->value
        );

        TagsStatusUpdate::run($command);

        return response()->json([
            'success' => true,
            'message' => __('message.success'),
            'data' => $command->attributes(),
        ], Response::HTTP_OK);
    }
}
