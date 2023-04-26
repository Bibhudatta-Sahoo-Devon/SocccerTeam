<?php

namespace Database\Seeders;

use App\Models\Players;
use Illuminate\Database\Seeder;

class PlayersSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Players::factory()
            ->count(5)
            ->create();
    }
}
