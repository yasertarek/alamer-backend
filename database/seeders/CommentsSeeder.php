<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Comment;
use App\Models\Blog;
use App\Models\User;
use Faker\Factory as Faker;

class CommentsSeeder extends Seeder
{
    public function run()
    {
        $faker = Faker::create();
        $blogs = Blog::all();
        $users = User::all();

        foreach ($blogs as $blog) {
            for ($i = 0; $i < rand(1, 5); $i++) {
                Comment::create([
                    'blog_id' => $blog->id,
                    'user_id' => $users->random()->id,
                    'content' => $faker->paragraph,
                ]);
            }
        }
    }
}
