<?php

use App\Http\Controllers\Project\ApiProjectController;
use App\Http\Controllers\Item\ApiConsumerItemController;
use Illuminate\Http\Request;
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

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::group([
    'middleware' => [
        'force.json',
        'auth:sanctum'
    ]
], function () {
    Route::post('/item/new', [ApiConsumerItemController::class, 'store']);
    Route::put('/item/{uuid}', [ApiConsumerItemController::class, 'update']);
    Route::get('/item/{uuid}', [ApiConsumerItemController::class, 'show']);

    Route::get('/project', [ApiProjectController::class, 'index']);
    Route::get('/project/{project}', [ApiProjectController::class, 'show']);
    Route::post('/project/new', [ApiProjectController::class, 'store']);
    Route::put('/project/{project}', [ApiProjectController::class, 'update']);
    Route::get('/project/{project}', [ApiProjectController::class, 'show']);
    Route::delete('/project/{project}', [ApiProjectController::class, 'destroy']);
});
