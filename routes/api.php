<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use \App\Http\Controllers\Api\V1\LoginController;
use \App\Http\Controllers\Api\V1\TeamsController;
use \App\Http\Controllers\Api\V1\PlayersController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::group(['prefix' => 'v1', 'as' => 'api.'], function () {
    /* These API endpoints will be available for guest users */
    Route::post('/login', [LoginController::class, 'login'])->name('login');
    Route::get('/teams', [TeamsController::class, 'getAllTeams'])->name('show.all.teams');
    Route::get('/team/{teams}/players', [PlayersController::class, 'getTeamPlayers'])->name('show.team.players');

    Route::middleware(['auth:sanctum'])->group(function () {
        Route::get('/user', function (Request $request) {
            return $request->user();
        })->name('user');

        Route::middleware(['role'])->group(function () {
            Route::group(['prefix' => 'team', 'as' => 'team.'], function () {
                Route::post('/', [TeamsController::class, 'createTeam'])->name('create');
                /* The "prefix" will make the API URI as /api/v1/team/{id}
                The "as" will make the route name as  api.team.show */
                Route::get('/{id}', [TeamsController::class, 'getTeam'])->name('show');
                Route::put('/{id}', [TeamsController::class, 'updateTeam'])->name('edit');
                Route::delete('/{id}', [TeamsController::class, 'deleteTeam'])->name('delete');
            });

            Route::group(['prefix' => 'player', 'as' => 'player.'], function () {
                Route::post('/', [PlayersController::class, 'createPlayer'])->name('create');
                Route::get('/{id}', [PlayersController::class, 'getPlayer'])->name('show');
                Route::put('/{id}', [PlayersController::class, 'updatePlayer'])->name('edit');
                Route::delete('/{id}', [PlayersController::class, 'deletePlayer'])->name('delete');
            });
        });
    });
});
