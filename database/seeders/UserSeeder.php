<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // Admin User
        User::create([
            'name' => 'Soccer Admin',
            'email' => 'admin@soccer.com',
            'password' => Hash::make('admin@123'),
            'role' => 'A',
        ]);

        // Normal User
        User::create([
            'name' => 'User Soccer',
            'email' => 'user@soccer.com',
            'password' => Hash::make('user@123'),
            'role' => 'U',
        ]);
    }
}
