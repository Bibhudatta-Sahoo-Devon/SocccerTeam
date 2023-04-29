<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Api\V1\TeamsController;
use App\Http\Controllers\Controller;
use App\Http\Controllers\Api\V1\PlayersController;
use App\Http\Requests\StorePlayerRequest;
use App\Http\Requests\UpdatePlayerRequest;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Routing\Redirector;
use Illuminate\Support\Facades\Auth;

class PlayerController extends Controller
{
    protected $playersAPIController;
    protected $teamsAPIController;

    public function __construct(PlayersController $playersAPIController, TeamsController $teamsAPIController)
    {
        $this->playersAPIController = $playersAPIController;
        $this->teamsAPIController = $teamsAPIController;
    }

    /**
     * @param int $teamId
     * @return Application|Factory|View
     */
    public function getTeamPlayers(int $teamId)
    {
        $response = $this->teamsAPIController->getTeam($teamId);
        $team = [];
        if ($response->getStatusCode() == 200)
            $team = json_decode($response->getContent(), true);
        return view('player-list', ['data' => $team, 'admin' => Auth::user()->role == 'A']);

    }

    /**
     * @param int $teamId
     * @return Application|Factory|View
     */
    public function createPlayer(int $teamId)
    {
        $response = $this->teamsAPIController->getTeam($teamId);
        $team = [];
        if ($response->getStatusCode() == 200)
            $team = json_decode($response->getContent(), true);
        return view('player', ['data' => $team]);
    }

    /**
     * @param StorePlayerRequest $request
     * @return Application|RedirectResponse|Redirector
     */
    public function storeCreatePlayer(StorePlayerRequest $request)
    {
        $response = $this->playersAPIController->createPlayer($request);

        if ($response->getStatusCode() == 201)
            return redirect(route('createPlayer', $request->get('team')))->with(['message' => 'Player created successfully']);

        return redirect()->back()->withInput($request->all())->withErrors($response);
    }

    /**
     * @param int $id
     * @return Application|Factory|View|RedirectResponse
     */
    public function editPlayer(int $id)
    {
        $response = $this->playersAPIController->getPlayer($id);
        if ($response->getStatusCode() == 200)
            return view('player', ['data' => (array)$response->getData()]);

        return redirect()->back()->withErrors('Player is not present.');
    }

    /**
     * @param UpdatePlayerRequest $request
     * @param $id
     * @return Application|RedirectResponse|Redirector
     */
    public function updatePlayer(UpdatePlayerRequest $request, $id)
    {
        $response = $this->playersAPIController->updatePlayer($request, $id);

        if ($response->getStatusCode() == 202)
            return redirect(route('teamPlayers', $request->get('team')))->with(['message' => 'Player updated successfully']);

        return redirect()->back()->withInput($request->all())->withErrors($response);
    }

    /**
     * @param int $id
     * @return Application|RedirectResponse|Redirector
     */
    public function deletePlayer(int $id)
    {
        $response = $this->playersAPIController->getPlayer($id);
        if ($response->getStatusCode() == 200) {
            $teamID = $response->getData()->team_id;
            $response = $this->playersAPIController->deletePlayer($id);
            if ($response->getStatusCode() == 204)
                return redirect(route('teamPlayers', $teamID))->with(['message' => 'Player deleted successfully']);
        }
        return redirect()->back()->withErrors('Player is not present.');
    }

}
