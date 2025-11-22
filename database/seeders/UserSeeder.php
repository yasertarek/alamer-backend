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
                'name'       => 'علي خالد',
                'email'      => 'user1@example.com',
                'password'   => Hash::make('password123'),
                'role'       => 'user',
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'name'       => 'محمد عبدالعزيز',
                'email'      => 'user2@example.com',
                'password'   => Hash::make('password123'),
                'role'       => 'user',
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'name'       => 'خالد أمير',
                'email'      => 'user3@example.com',
                'password'   => Hash::make('password123'),
                'role'       => 'user',
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'name'       => 'عز الدين أحمد',
                'email'      => 'user4@example.com',
                'password'   => Hash::make('password123'),
                'role'       => 'user',
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'name'       => 'فهد ماجد',
                'email'      => 'user5@example.com',
                'password'   => Hash::make('password123'),
                'role'       => 'user',
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'name'       => 'عمر سالم',
                'email'      => 'user6@example.com',
                'password'   => Hash::make('password123'),
                'role'       => 'user',
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'name'       => 'محمد خالد',
                'email'      => 'user7@example.com',
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
