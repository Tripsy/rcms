<?php

use App\Commands\ProjectStoreCommand;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\Project\ProjectController;
use App\Repositories\Interfaces\ProjectRepositoryInterface;
use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/test', function (\App\Queries\ProjectDeleteQuery $projectDeleteQuery) {
//    $items = Item::all();
//    $item = Item::query()
//        ->uuid('99abffb3-7973-42cf-a7ac-ce484ef714f0')
//        ->first();
//
//        try {
//            $item = $itemRepository->findByUuid('99abffb3-7973-42cf-a7ac-ce484ef714f0');
//        } catch (ModelNotFoundException $exception) {
//            return response("Item not found", ResponseAlias::HTTP_NOT_FOUND);
//        }
//
//
//    dd('what');

      $projectDeleteQuery->delete();

});

Route::get('/account/show/{id}', [ProjectController::class, 'show']);

Route::get('/', function () {
    return Inertia::render('Welcome', [
        'canLogin' => Route::has('login'),
        'canRegister' => Route::has('register'),
        'laravelVersion' => Application::VERSION,
        'phpVersion' => PHP_VERSION,
    ]);
});

Route::get('/dashboard', function () {
    return Inertia::render('Dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';
