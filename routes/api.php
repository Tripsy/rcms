<?php

use App\Http\Controllers\Item\ApiConsumerItemController;
use App\Http\Controllers\Project\ApiProjectController;
use App\Http\Controllers\Project\ApiProjectStatusController;
use App\Http\Controllers\ProjectBlueprint\ApiProjectBlueprintController;
use App\Http\Controllers\ProjectPermission\ApiProjectBlueprintStatusController;
use App\Http\Controllers\ProjectPermission\ApiProjectPermissionController;
use App\Http\Controllers\ProjectPermission\ApiProjectPermissionStatusController;
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
    Route::post('/item/new', [ApiConsumerItemController::class, 'store']);
    Route::put('/item/{uuid}', [ApiConsumerItemController::class, 'update']);
    Route::get('/item/{uuid}', [ApiConsumerItemController::class, 'show']);

    Route::controller(ApiProjectController::class)->group(function () {
        Route::get('/project', 'index');

        Route::get('/project/{project}', 'show')
            ->where('project', '[0-9]+');

        Route::post('/project', 'store');

        Route::put('/project/{project}', 'update')
            ->where('project', '[0-9]+');

        Route::delete('/project/{project}', 'destroy')
            ->where('project', '[0-9]+');
    });

    Route::patch('/project/{project}/{status}', ApiProjectStatusController::class)
        ->where('project', '[0-9]+');

    Route::controller(ApiProjectPermissionController::class)->group(function () {
        Route::get('/project-permission/{project}', 'index')
            ->where('project', '[0-9]+');

        Route::get('/project-permission/{project}/{projectPermission}', 'show')
            ->where('project', '[0-9]+')
            ->where('projectPermission', '[0-9]+');

        Route::post('/project-permission/{project}', 'store')
            ->where('project', '[0-9]+');

        Route::put('/project-permission/{project}/{projectPermission}', 'update')
            ->where('project', '[0-9]+')
            ->where('projectPermission', '[0-9]+');

        Route::delete('/project-permission/{project}/{projectPermission}', 'destroy')
            ->where('project', '[0-9]+')
            ->where('projectPermission', '[0-9]+');
    });

    Route::patch(
        '/project-permission/{project}/{projectPermission}/{status}',
        ApiProjectPermissionStatusController::class
    )
        ->where('project', '[0-9]+')
        ->where('projectPermission', '[0-9]+');

    Route::controller(ApiProjectBlueprintController::class)->group(function () {
        Route::get('/project-blueprint/{project}', 'index')
            ->where('project', '[0-9]+');

        Route::get('/project-blueprint/{project}/{projectBlueprint}', 'show')
            ->where('project', '[0-9]+')
            ->where('projectBlueprint', '[0-9]+');

        Route::post('/project-blueprint/{project}', 'store')
            ->where('project', '[0-9]+');

        Route::put('/project-blueprint/{project}/{projectBlueprint}', 'update')
            ->where('project', '[0-9]+')
            ->where('projectBlueprint', '[0-9]+');

        Route::delete('/project-blueprint/{project}/{projectBlueprint}', 'destroy')
            ->where('project', '[0-9]+')
            ->where('projectBlueprint', '[0-9]+');
    });

    Route::patch(
        '/project-blueprint/{project}/{projectBlueprint}/{status}',
        ApiProjectBlueprintStatusController::class
    )
        ->where('project', '[0-9]+')
        ->where('projectBlueprint', '[0-9]+');
});
