<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Carbon\Carbon;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        $now = Carbon::now();

        DB::table('users')->insert([
            [
                'name'       => 'Regular User One',
                'email'      => 'user1@example.com',
                'password'   => Hash::make('password123'),
                'role'       => 'user',
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'name'       => 'Regular User Two',
                'email'      => 'user2@example.com',
                'password'   => Hash::make('password123'),
                'role'       => 'user',
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'name'       => 'Moderator Admin',
                'email'      => 'moderator@example.com',
                'password'   => Hash::make('password123'),
                'role'       => 'moderator',
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'name'       => 'Supervisor Admin',
                'email'      => 'supervisor@example.com',
                'password'   => Hash::make('password123'),
                'role'       => 'supervisor',
                'created_at' => $now,
                'updated_at' => $now,
            ],
        ]);
    }
}
