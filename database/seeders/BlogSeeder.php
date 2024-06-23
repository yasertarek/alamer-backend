<?php
namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Blog;
use App\Models\User;

class BlogSeeder extends Seeder
{
    public function run()
    {
        $user = User::first();
        if (!$user) {
            $this->command->error('No user found. Please ensure the UserSeeder has been run and a user exists.');
            return;
        }

        Blog::create([
            'user_id' => $user->id,
        ]);
    }
}
