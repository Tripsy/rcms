<?php

use App\Http\Controllers\BlueprintComponent\ApiBlueprintComponentController;
use App\Http\Controllers\BlueprintComponent\ApiBlueprintComponentStatusController;
use App\Http\Controllers\Item\ApiItemController;
use App\Http\Controllers\Item\ApiItemStatusController;
use App\Http\Controllers\Project\ApiProjectController;
use App\Http\Controllers\Project\ApiProjectStatusController;
use App\Http\Controllers\ProjectBlueprint\ApiProjectBlueprintController;
use App\Http\Controllers\ProjectBlueprint\ApiProjectBlueprintStatusController;
use App\Http\Controllers\ProjectPermission\ApiProjectPermissionController;
use App\Http\Controllers\ProjectPermission\ApiProjectPermissionStatusController;
use App\Http\Controllers\Tags\ApiTagsController;
use App\Http\Controllers\Tags\ApiTagsStatusController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

//Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
//    return $request->user();
//});

Route::group([
    'middleware' => [
        'auth:sanctum',
    ],
], function () {
    Route::prefix('/project')->group(function () {
        Route::controller(ApiProjectController::class)->group(function () {
            Route::get('', 'index');

            Route::get('/{project}', 'show')
                ->where('project', '[0-9]+');

            Route::post('', 'store');

            Route::put('{project}', 'update')
                ->where('project', '[0-9]+');

            Route::delete('/{project}', 'destroy')
                ->where('project', '[0-9]+');
        });

        Route::patch('/{project}/{status}', ApiProjectStatusController::class)
            ->where('project', '[0-9]+');
    });

    Route::prefix('/project-permission')->group(function () {
        Route::controller(ApiProjectPermissionController::class)->group(function () {
            Route::get('/{project}', 'index')
                ->where('project', '[0-9]+');

            Route::get('/{project}/{projectPermission}', 'show')
                ->where('project', '[0-9]+')
                ->where('projectPermission', '[0-9]+');

            Route::post('/{project}', 'store')
                ->where('project', '[0-9]+');

            Route::put('/{project}/{projectPermission}', 'update')
                ->where('project', '[0-9]+')
                ->where('projectPermission', '[0-9]+');

            Route::delete('/{project}/{projectPermission}', 'destroy')
                ->where('project', '[0-9]+')
                ->where('projectPermission', '[0-9]+');
        });

        Route::patch(
            '/{project}/{projectPermission}/{status}',
            ApiProjectPermissionStatusController::class
        )
            ->where('project', '[0-9]+')
            ->where('projectPermission', '[0-9]+');
    });

    Route::prefix('/project-blueprint')->group(function () {
        Route::controller(ApiProjectBlueprintController::class)
            ->group(function () {
                Route::get('/{project}', 'index')
                    ->where('project', '[0-9]+');

                Route::get('/{project}/{projectBlueprint}', 'show')
                    ->where('project', '[0-9]+')
                    ->where('projectBlueprint', '[0-9]+');

                Route::post('/{project}', 'store')
                    ->where('project', '[0-9]+');

                Route::put('/{project}/{projectBlueprint}', 'update')
                    ->where('project', '[0-9]+')
                    ->where('projectBlueprint', '[0-9]+');

                Route::delete('/{project}/{projectBlueprint}', 'destroy')
                    ->where('project', '[0-9]+')
                    ->where('projectBlueprint', '[0-9]+');
            });

        Route::patch(
            '/{project}/{projectBlueprint}/{status}',
            ApiProjectBlueprintStatusController::class
        )
            ->where('project', '[0-9]+')
            ->where('projectBlueprint', '[0-9]+');
    });

    Route::prefix('/blueprint-component')->group(function () {
        Route::controller(ApiBlueprintComponentController::class)->group(function () {
            Route::get('/{projectBlueprint}', 'index')
                ->where('projectBlueprint', '[0-9]+');

            Route::get('/{projectBlueprint}/{blueprintComponent}', 'show')
                ->where('projectBlueprint', '[0-9]+')
                ->where('blueprintComponent', '[0-9]+');

            Route::post('/{projectBlueprint}', 'store')
                ->where('projectBlueprint', '[0-9]+');

            Route::put('/{projectBlueprint}/{blueprintComponent}', 'update')
                ->where('projectBlueprint', '[0-9]+')
                ->where('blueprintComponent', '[0-9]+');

            Route::delete('/{projectBlueprint}/{blueprintComponent}', 'destroy')
                ->where('projectBlueprint', '[0-9]+')
                ->where('blueprintComponent', '[0-9]+');
        });

        Route::patch(
            '/{projectBlueprint}/{blueprintComponent}/{status}',
            ApiBlueprintComponentStatusController::class
        )
            ->where('projectBlueprint', '[0-9]+')
            ->where('blueprintComponent', '[0-9]+');
    });

    Route::prefix('/tags')->group(function () {
        Route::controller(ApiTagsController::class)->group(function () {
            Route::get('/{project}', 'index')
                ->where('project', '[0-9]+');

            Route::get('/{project}/{tags}', 'show')
                ->where('project', '[0-9]+')
                ->where('tags', '[0-9]+');

            Route::post('/{project}', 'store')
                ->where('project', '[0-9]+');

            Route::put('/{project}/{tags}', 'update')
                ->where('project', '[0-9]+')
                ->where('tags', '[0-9]+');

            Route::delete('/{project}/{tags}', 'destroy')
                ->where('project', '[0-9]+')
                ->where('tags', '[0-9]+');
        });

        Route::patch(
            '/{project}/{tags}/{status}',
            ApiTagsStatusController::class
        )
            ->where('project', '[0-9]+')
            ->where('tags', '[0-9]+');
    });

    Route::prefix('/item')->group(function () {
        Route::controller(ApiItemController::class)->group(function () {
            Route::get('/{projectBlueprint}', 'index')
                ->where('projectBlueprint', '[0-9]+');

            Route::get('/{projectBlueprint}/{item}', 'show')
                ->where('projectBlueprint', '[0-9]+')
                ->where('item', '[0-9]+');

            Route::post('/{projectBlueprint}', 'store')
                ->where('projectBlueprint', '[0-9]+');

            Route::put('/{projectBlueprint}/{item}', 'update')
                ->where('projectBlueprint', '[0-9]+')
                ->where('item', '[0-9]+');

            Route::delete('/{projectBlueprint}/{item}', 'destroy')
                ->where('projectBlueprint', '[0-9]+')
                ->where('item', '[0-9]+');
        });

        Route::patch(
            '/{projectBlueprint}/{item}/{status}',
            ApiItemStatusController::class
        )
            ->where('projectBlueprint', '[0-9]+')
            ->where('item', '[0-9]+');
    });
});
