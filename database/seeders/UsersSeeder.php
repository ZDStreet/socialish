<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UsersSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Create Zak Street user
        User::factory()->create([
            'name' => 'Zak Street',
            'email' => 'zak.david.street@outlook.com',
            'password' => Hash::make('password'),
        ]);

        // Create 24 random users
        User::factory(24)->make()->each(function ($user) {
            $user->save();
        });
    }
}
