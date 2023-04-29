<?php

use Illuminate\Support\Facades\Route;
use \App\Http\Controllers\Web\TeamController;
use \App\Http\Controllers\Web\PlayerController;
use App\Http\Controllers\Auth\RegisteredUserController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register Web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "Web" middleware group. Now create something great!
|
*/


Route::get('/register', [RegisteredUserController::class, 'create']);
Route::post('/register', [RegisteredUserController::class, 'store'])->name('register');
Route::get('/', function () {
    return view('welcome');
});
Route::get('/team/{teams}/players', [PlayerController::class, 'getTeamPlayers'])->name('teamPlayers');

Route::middleware(['auth'])->group(function () {

    Route::get('/dashboard', [TeamController::class, 'dashboard'])->name('dashboard');

    Route::middleware(['role:admin'])->group(function () {

        Route::prefix('team')->group(function() {
            Route::get('/', [TeamController::class, 'createTeam'])->name('createTeam');
            Route::post('/create', [TeamController::class, 'storeCreateTeam']);
            Route::get('/{teams}', [TeamController::class, 'editTeam'])->name('editTeam');
            Route::post('/{teams}', [TeamController::class, 'updateTeam'])->name('storeTeam');
            Route::get('/delete/{teams}', [TeamController::class, 'deleteTeam'])->name('deleteTeam');
        });

        Route::prefix('player')->group(function() {
            Route::get('/create/{teams}', [PlayerController::class, 'createPlayer'])->name('createPlayer');
            Route::post('/create', [PlayerController::class, 'storeCreatePlayer'])->name('storePlayer');
            Route::get('/{players}', [PlayerController::class, 'editPlayer'])->name('editPlayer');
            Route::post('/{players}', [PlayerController::class, 'updatePlayer']);
            Route::get('/delete/{players}', [PlayerController::class, 'deletePlayer'])->name('deletePlayer');
        });
    });


});


require __DIR__ . '/auth.php';
