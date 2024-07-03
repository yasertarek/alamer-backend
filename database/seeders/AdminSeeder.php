<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Admin;
use Illuminate\Support\Facades\Hash;

class AdminSeeder extends Seeder
{
    public function run()
    {
        // Creating supervisor admin
        $supervisorUser = User::create([
            'name' => 'Supervisor Admin',
            'email' => 'supervisor@example.com',
            'password' => Hash::make('password'),
        ]);

        Admin::create([
            'name' => 'Supervisor Admin',
            'email' => 'supervisor@example.com',
            'password' => Hash::make('password'),
            'user_id' => $supervisorUser->id,
            'role' => 'supervisor',
        ]);

        // Creating moderator admin
        $moderatorUser = User::create([
            'name' => 'Moderator Admin',
            'email' => 'moderator@example.com',
            'password' => Hash::make('password'),
        ]);

        Admin::create([
            'name' => 'Moderator Admin',
            'email' => 'moderator@example.com',
            'password' => Hash::make('password'),
            'user_id' => $moderatorUser->id,
            'role' => 'moderator',
        ]);
    }
}
