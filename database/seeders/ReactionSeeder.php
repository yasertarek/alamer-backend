<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

use App\Models\Reaction;
use App\Models\Blog;
use App\Models\User;
use Faker\Factory as Faker;

class ReactionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $faker = Faker::create();
        $blogs = Blog::all();
        $users = User::all();

        foreach ($blogs as $blog) {
            for ($i = 0; $i < rand(1, 5); $i++) {
                Reaction::create([
                    'blog_id' => $blog->id,
                    'user_id' => $users->random()->id,
                    'type' => $faker->randomElement(["like", "insightful", "love", "angry"]),
                ]);
            }
        }
    }
}
