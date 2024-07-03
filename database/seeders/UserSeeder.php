<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run()
    {
        User::factory()->count(3)->create([
            'password' => Hash::make('password123'), // Default password for all users
            'profile_picture' => "https://www.alamer-co.com/imgs/og-bg.png"
        ]);
    }
}
