<?php

use App\Http\Controllers\Account\ApiAccountController;
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
    Route::post('/account/new', [ApiAccountController::class, 'store']);
    Route::post('/item/new', [ApiConsumerItemController::class, 'store']);
    Route::put('/item/{uuid}', [ApiConsumerItemController::class, 'update']);
    Route::get('/item/{uuid}', [ApiConsumerItemController::class, 'show']);
});
