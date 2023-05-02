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

    /*
     * Using Player & Team API controller
     */
    public function __construct(PlayersController $playersAPIController, TeamsController $teamsAPIController)
    {
        $this->playersAPIController = $playersAPIController;
        $this->teamsAPIController = $teamsAPIController;
    }

    /**
     * Desc : To get details of a team's all players
     * @param int $teamId
     * @return Application|Factory|View
     */
    public function getTeamPlayers(int $teamId)
    {
        $teamResponse = $this->teamsAPIController->getTeam($teamId);
        $team = [];
        if ($teamResponse->getStatusCode() == 200)
            $team = json_decode($teamResponse->getContent(), true);
        return view('player-list', ['data' => $team, 'admin' => Auth::user()->role == 'A']);

    }

    /**
     * Desc : To show create player form page
     * @param int $teamId
     * @return Application|Factory|View
     */
    public function createPlayer(int $teamId)
    {
        $createResponse = $this->teamsAPIController->getTeam($teamId);
        $team = [];
        if ($createResponse->getStatusCode() == 200)
            $team = json_decode($createResponse->getContent(), true);
        return view('player', ['data' => $team]);
    }

    /**
     * Desc : To validate & create a player by storing it's details in database
     * @param StorePlayerRequest $request
     * @return Application|RedirectResponse|Redirector
     */
    public function storeCreatePlayer(StorePlayerRequest $request)
    {
        $createResponse = $this->playersAPIController->createPlayer($request);

        if ($createResponse->getStatusCode() == 201)
            return redirect(route('createPlayer', $request->get('team')))->with(['message' => 'Player created successfully']);

        return redirect()->back()->withInput($request->all())->withErrors($createResponse);
    }

    /**
     * Desc : To show the edit player page with player details
     * @param int $id
     * @return Application|Factory|View|RedirectResponse
     */
    public function editPlayer(int $id)
    {
        $editResponse = $this->playersAPIController->getPlayer($id);
        if ($editResponse->getStatusCode() == 200)
            return view('player', ['data' => (array)$editResponse->getData()]);

        return redirect()->back()->withErrors('Player is not present.');
    }

    /**
     * Desc : To save all updated details of a player
     * @param UpdatePlayerRequest $request
     * @param $id
     * @return Application|RedirectResponse|Redirector
     */
    public function updatePlayer(UpdatePlayerRequest $request, $id)
    {
        $updateResponse = $this->playersAPIController->updatePlayer($request, $id);

        if ($updateResponse->getStatusCode() == 202)
            return redirect(route('teamPlayers', $request->get('team')))->with(['message' => 'Player updated successfully']);

        return redirect()->back()->withInput($request->all())->withErrors($updateResponse);
    }

    /**
     * To delete a player details from database
     * @param int $id
     * @return Application|RedirectResponse|Redirector
     */
    public function deletePlayer(int $id)
    {
        $playerResponse = $this->playersAPIController->getPlayer($id);
        if ($playerResponse->getStatusCode() == 200) {
            $teamID = $playerResponse->getData()->team_id;
            $deleteResponse = $this->playersAPIController->deletePlayer($id);
            if ($deleteResponse->getStatusCode() == 204)
                return redirect(route('teamPlayers', $teamID))->with(['message' => 'Player deleted successfully']);
        }
        return redirect()->back()->withErrors('Player is not present.');
    }

}
