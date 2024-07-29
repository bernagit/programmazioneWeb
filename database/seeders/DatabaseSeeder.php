<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        DB::table('users')->insert([
            'name' => 'alessandro',
            'email' => 'alex.bernocchi.ab@gmail.com',
            'role' => 'admin',
            'password' => Hash::make('password'),
        ]);
    }
}
