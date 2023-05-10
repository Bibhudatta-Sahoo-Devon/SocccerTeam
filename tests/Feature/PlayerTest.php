<?php

namespace Tests\Feature;

use App\Models\Players;
use App\Models\Teams;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class PlayerTest extends TestCase
{
    use RefreshDatabase;


    public function test_get_all_player_of_team()
    {

        $team = Teams::factory()->create();
        $player = Players::factory()->count(10)->create([
            'team_id' => $team->id
        ]);

        $response = $this->get(route('api.show.team.players', ['teams' => $team->id]));
        $response->assertStatus(200);
        $response->assertJson(['data' => $player->toArray()]);
    }

    public function test_wrong_team_id_to_get_all_player()
    {

        $team = Teams::factory()->create();
        Players::factory()->count(10)->create([
            'team_id' => $team->id
        ]);

        $response = $this->get(route('api.show.team.players', ['teams' => 0]));
        $response->assertStatus(200);
        $response->assertJson(['data' => []]);
    }

    public function test_create_a_player()
    {

        $user = User::factory()->create();
        $team = Teams::factory()->create();

        $response = $this->actingAs($user)->postJson(route('api.player.create'), [
            'first_name' => 'mark',
            'last_name' => 'star',
            'image' => new \Illuminate\Http\UploadedFile(public_path('test-files/sc.jpg'), 'sc.jpg', null, null, true),
            'team' => $team->id
        ]);
        $response->assertStatus(201);
    }

    public function test_create_a_player_with_invalid_data()
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)->postJson(route('api.player.create'), [
            'first_name' => 'mark',
            'last_name' => 'star',
            'image' => 'AAsa/AsaS/AsSA.jpg'
        ]);
        $response->assertStatus(422);
    }

    public function test_create_a_player_without_login()
    {
        $team = Teams::factory()->create();

        $response = $this->postJson(route('api.player.create'), [
            'first_name' => 'mark',
            'last_name' => 'star',
            'image' => new \Illuminate\Http\UploadedFile(public_path('test-files/sc.jpg'), 'sc.jpg', null, null, true),
            'team_id' => $team->id
        ]);
        $response->assertStatus(401);
    }

    public function test_fetch_a_player_details()
    {
        $user = User::factory()->create();
        $team = Teams::factory()->create();
        $player = Players::factory()->create([
            'team_id' => $team->id
        ]);

        $response = $this->actingAs($user)->get(route('api.player.show', ['id' => $player->id]));
        $response->assertStatus(200);
        $response->assertJsonFragment($player->toArray());
    }

    public function test_edit_a_player()
    {
        $user = User::factory()->create();
        $team = Teams::factory()->create();
        $player = Players::factory()->create(['team_id' => $team->id]);

        $response = $this->actingAs($user)->putJson(route('api.player.edit', ['id' => $player->id]), [
            'first_name' => 'test1',
            'last_name' => 'two',
        ]);
        $response->assertStatus(202);

    }

    public function test_edit_a_player_with_invalid_data()
    {

        $user = User::factory()->create();
        $player = Players::factory()->create();

        $response = $this->actingAs($user)->putJson(route('api.player.edit', ['id' => $player->id]), [
            'name' => new \Illuminate\Http\UploadedFile(public_path('test-files/sc.jpg'), 'sc.jpg', null, null, true)
        ]);
        $response->assertStatus(422);
        $response->assertJsonMissing(['massage' => 'Players updated successfully']);
    }

    public function test_edit_a_player_without_authentication()
    {

        $player = Players::factory()->create();

        $response = $this->putJson(route('api.player.edit', ['id' => $player->id]), [
            'name' => new \Illuminate\Http\UploadedFile(public_path('test-files/sc.jpg'), 'sc.jpg', null, null, true)
        ]);
        $response->assertStatus(401);
        $response->assertJsonMissing(['massage' => 'Players updated successfully']);
    }

    public function test_delete_a_player()
    {
        $user = User::factory()->create();
        $player = Players::factory()->create();

        $response = $this->actingAs($user)->deleteJson(route('api.player.delete', ['id' => $player->id]));
        $response->assertStatus(200);
    }

    public function test_delete_a_player_without_login()
    {
        $player = Players::factory()->create();

        $response = $this->deleteJson(route('api.player.delete', ['id' => $player->id]));
        $response->assertStatus(401);
    }
}
