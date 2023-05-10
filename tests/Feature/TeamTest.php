<?php

namespace Tests\Feature;

use App\Models\Teams;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class TeamTest extends TestCase
{
    use RefreshDatabase;

    public function test_get_all_team()
    {

        Teams::factory()->count(10)->create();

        $response = $this->getJson(route('api.show.all.teams'));
        $response->assertStatus(200);
        $response->assertJsonCount(10,'data.*');
    }

    public function test_get_teams_when_no_team_presents()
    {

        $response = $this->getJson(route('api.show.all.teams'));
        $response->assertStatus(200);
        $response->assertJson(['data' => []]);
    }

    public function test_fetch_a_team_details()
    {

        $user = User::factory()->create();
        $team = Teams::factory()->create();

        $response = $this->actingAs($user)->getJson(route('api.team.show', ['id' => $team->id]));
        $response->assertStatus(200);
        $response->assertJsonFragment($team->toArray());
    }

    public function test_create_a_team()
    {

        $user = User::factory()->create();

        $response = $this->actingAs($user)->postJson(route('api.team.create'), [
            'name' => 'Driver GTeam',
            'logo' => new \Illuminate\Http\UploadedFile(public_path('test-files/sc.jpg'), 'sc.jpg', null, null, true)
        ]);
        $response->assertStatus(201);
    }

    public function test_create_with_invalid_data_a_team()
    {

        $user = User::factory()->create();

        $response = $this->actingAs($user)->postJson(route('api.team.create'), [
            'name' => 'Driver GTeam',
            'logo' => 'test-file/sc.jpg'
        ]);
        $response->assertStatus(422);
    }

    public function test_create_a_team_without_login()
    {


        $response = $this->postJson(route('api.team.create'), [
            'name' => 'Driver GTeam',
            'logo' => new \Illuminate\Http\UploadedFile(public_path('test-files/sc.jpg'), 'sc.jpg', null, null, true)
        ]);
        $response->assertStatus(401);
    }

    public function test_edit_a_team()
    {

        $user = User::factory()->create();
        $team = Teams::factory()->create();

        $response = $this->actingAs($user)->putJson(route('api.team.edit', ['id' => $team->id]), [
            'name' => 'Gteam',
        ]);
        $response->assertStatus(202);
    }

    public function test_edit_a_team_with_invalid_data()
    {

        $user = User::factory()->create();
        $team = Teams::factory()->create();

        $response = $this->actingAs($user)->putJson(route('api.team.edit', ['id' => $team->id]), [
            'name' => new \Illuminate\Http\UploadedFile(public_path('test-files/sc.jpg'), 'sc.jpg', null, null, true)
        ]);
        $response->assertStatus(422);
        $response->assertJsonMissing(['massage' => 'Update Team successfully']);
    }

    public function test_delete_a_team()
    {
        $user = User::factory()->create();
        $team = Teams::factory()->create();

        $response = $this->actingAs($user)->deleteJson(route('api.team.delete', ['id' => $team->id]));
        $response->assertStatus(200);
    }

    public function test_delete_a_team_without_login()
    {
        $team = Teams::factory()->create();

        $response = $this->deleteJson(route('api.team.delete', ['id' => $team->id]));
        $response->assertStatus(401);
    }


}
