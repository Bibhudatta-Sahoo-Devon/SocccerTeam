<?php

namespace Database\Seeders;

use App\Models\Teams;
use Database\Factories\TeamsFactory;
use Illuminate\Database\Seeder;

class TeamsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
       Teams::factory()
           ->count(5)
           ->create();
    }
}
