<?php

declare(strict_types=1);

namespace App\Http\Controllers\Tags;

use App\Actions\TagsDelete;
use App\Actions\TagsStore;
use App\Actions\TagsUpdate;
use App\Commands\TagsDeleteCommand;
use App\Commands\TagsStoreCommand;
use App\Commands\TagsUpdateCommand;
use App\Exceptions\ControllerException;
use App\Http\Controllers\Controller;
use App\Http\Requests\TagsIndexRequest;
use App\Http\Requests\TagsStoreRequest;
use App\Http\Requests\TagsUpdateRequest;
use App\Models\Project;
use App\Models\Tag;
use App\Queries\TagQuery;
use App\Repositories\TagsRepository;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Gate;
use Symfony\Component\HttpFoundation\Response;
use Tripsy\ApiWrapper\ApiWrapper;

class ApiTagsController extends Controller
{
    private ApiWrapper $apiWrapper;

    public function __construct(ApiWrapper $apiWrapper)
    {
        $this->apiWrapper = $apiWrapper;
    }

    /**
     * Display a listing of the resource.
     */
    public function index(
        TagsIndexRequest $request,
        Project $project,
        TagQuery $query
    ): JsonResponse {
        Gate::authorize('index', [Tag::class, $project]);

        $validated = $request->validated();

        $results = $query
            ->filterByProjectId($project->id)
            ->filterByName('%'.$validated['filter']['name'].'%', 'LIKE')
            ->isCategory($validated['filter']['is_category'])
            ->filterByStatus($validated['filter']['status'])
            ->withCreatedBy()
            ->withUpdatedBy()
            ->get($validated['page'], $validated['limit'])
            ->makeHidden(['user_id']);

        $this->apiWrapper->success(true);
        $this->apiWrapper->message(__('message.success'));
        $this->apiWrapper->data([
            'results' => $results,
            'filter' => array_filter($validated['filter']),
            'count' => count($results),
            'limit' => $validated['limit'],
            'page' => $validated['page'],
        ]);

        return response()->json($this->apiWrapper->resultArray(), Response::HTTP_OK);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @throws ControllerException
     */
    public function store(
        TagsStoreRequest $request,
        Project $project,
        TagQuery $query
    ): JsonResponse {
        Gate::authorize('create', [Tag::class, $project]);

        $validated = $request->validated();

        try {
            $commandTags = new TagsStoreCommand(
                $project->id,
                $validated['name'],
                $validated['description'],
                $validated['is_category'],
                $validated['status'],
            );

            TagsStore::run($commandTags);

            $tags = $query
                ->filterByProjectId($commandTags->getProjectId())
                ->filterByName($commandTags->getName())
                ->firstOrFail();
        } catch (ModelNotFoundException) {
            throw new ControllerException(
                __('message.tags.store_fail'),
                Response::HTTP_INTERNAL_SERVER_ERROR
            );
        }

        $this->apiWrapper->success(true);
        $this->apiWrapper->message(__('message.success'));
        $this->apiWrapper->data(array_merge(
            [
                'id' => $tags->id,
            ],
            $commandTags->attributes()
        ));

        return response()->json($this->apiWrapper->resultArray(), Response::HTTP_CREATED);
    }

    /**
     * Display the specified resource.
     */
    public function show(
        Project        $project,
        Tag            $tags,
        TagQuery       $query,
        TagsRepository $repository
    ): JsonResponse {
        Gate::authorize('view', [Tag::class, $project]);

        $data = $repository->getViewCache($tags->id, function () use ($query, $project, $tags) {
            return $query
                ->filterById($tags->id)
                ->filterByProjectId($project->id)
                ->withCreatedBy()
                ->withUpdatedBy()
                ->first();
        });

        $this->apiWrapper->success(true);
        $this->apiWrapper->message(__('message.success'));
        $this->apiWrapper->pushMeta('isCached', $repository->isCached());
        $this->apiWrapper->data($data);

        return response()->json($this->apiWrapper->resultArray(), Response::HTTP_OK);
    }

    /**
     * Update the specified resource in storage.
     *
     * @throws ControllerException
     */
    public function update(
        TagsUpdateRequest $request,
        Project $project,
        Tag $tags
    ): JsonResponse {
        Gate::authorize('update', [Tag::class, $project]);

        $validated = $request->validated();

        try {
            $command = new TagsUpdateCommand(
                $tags->id,
                $project->id,
                $validated['name'],
                $validated['description'],
                $validated['is_category'],
            );

            TagsUpdate::run($command);
        } catch (ModelNotFoundException) {
            throw new ControllerException(
                __('message.tags.store_fail'),
                Response::HTTP_INTERNAL_SERVER_ERROR
            );
        }

        $this->apiWrapper->success(true);
        $this->apiWrapper->message(__('message.success'));
        $this->apiWrapper->data($command->attributes());

        return response()->json($this->apiWrapper->resultArray(), Response::HTTP_OK);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Project $project, Tag $tags): JsonResponse
    {
        Gate::authorize('delete', [Tag::class, $project]);

        $command = new TagsDeleteCommand(
            $tags->id,
            $project->id
        );

        TagsDelete::run($command);

        $this->apiWrapper->success(true);
        $this->apiWrapper->message(__('message.success'));
        $this->apiWrapper->data($command->attributes());

        return response()->json($this->apiWrapper->resultArray(), Response::HTTP_ACCEPTED);
    }
}
