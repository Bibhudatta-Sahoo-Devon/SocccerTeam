<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Api\V1\TeamsController;
use App\Http\Requests\StoreTeamRequest;
use App\Http\Requests\UpdateTeamRequest;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Routing\Redirector;
use Illuminate\Support\Facades\Auth;

class TeamController extends Controller
{
    protected $teamsAPIController;

    /*
     * Using Team API controller
     */
    public function __construct(TeamsController $teamsAPIController)
    {
        $this->teamsAPIController = $teamsAPIController;
    }

    /**
     * Desc : To show dashboard page with all team details
     * @return Application|Factory|View
     */
    public function dashboard()
    {
        $teams = $this->teamsAPIController->getAllTeams();
        $data = [];
        if ($teams->getStatusCode() == 200) {
            $data = json_decode($teams->getContent(), true);
            $data = $data['data'];
        }
        return view('dashboard', ['data' => $data, 'admin' => Auth::user()->role == 'A'])->with(['message' => 'Team updated successfully']);
    }

    /**
     * Desc : To show form page to create a team
     * @return Application|Factory|View
     */
    public function createTeam()
    {
        return view('team');
    }


    /**
     * Desc : To validate & create a team by storing it's details in database
     * @param StoreTeamRequest $request
     * @return Application|RedirectResponse|Redirector
     */
    public function storeCreateTeam(StoreTeamRequest $request)
    {
        $createResponse = $this->teamsAPIController->createTeam($request);
        if ($createResponse->getStatusCode() == 201)
            return redirect('dashboard')->with(['message' => 'Team created successfully']);

        return redirect()->back()->withInput($request->all())->withErrors($createResponse);
    }


    /**
     * Desc : To show form page with team details to edit
     * @param int $id
     * @return Application|Factory|View
     */
    public function editTeam(int $id)
    {
        $team = $this->teamsAPIController->getTeam($id);
        $data = [];
        if ($team->getStatusCode() == 200) {
            $data = json_decode($team->getContent(), true);
        }
        return view('team', ['data' => $data]);
    }


    /**
     * Desc : To validate & save updated team details in database
     * @param UpdateTeamRequest $request
     * @param  $id
     * @return Application|RedirectResponse|Redirector
     */
    public function updateTeam(UpdateTeamRequest $request, $id)
    {
        $updateResponse = $this->teamsAPIController->updateTeam($request, $id);
        if ($updateResponse->getStatusCode() == 202)
            return redirect('dashboard')->with(['message' => 'Team updated successfully']);

        return redirect()->back()->withInput($request->all())->withErrors($updateResponse);
    }


    /**
     * Desc : To delete team details from database
     * @param  $id
     * @return Application|RedirectResponse|Redirector
     */
    public function deleteTeam($id)
    {
        $deleteResponse = $this->teamsAPIController->deleteTeam($id);
        if ($deleteResponse->getStatusCode() == 200)
            return redirect('dashboard')->with(['message' => 'Team successfully deleted!']);

        return redirect()->back()->withErrors($deleteResponse);
    }
}
