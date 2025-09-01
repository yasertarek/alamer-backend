<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run()
    {
        $this->call([
            LanguageSeeder::class, // Ensure this seeder is called before others
            RoleSeeder::class,
            CatsSeeder::class,
            AdminSeeder::class,
            UserSeeder::class,
            BlogSeeder::class,
            ServiceSeeder::class,
            // CommentsSeeder::class,
            // ReactionSeeder::class,
            NavbarSeeder::class,
        ]);
    }
}
