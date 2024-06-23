<?php

namespace Database\Factories;

use App\Models\Blog;
use App\Models\Language;
use App\Models\BlogTranslation;
use Illuminate\Database\Eloquent\Factories\Factory;

class BlogTranslationFactory extends Factory
{
    protected $model = BlogTranslation::class;

    public function definition()
    {
        return [
            'blog_id' => Blog::factory(),
            'language_id' => Language::inRandomOrder()->first()->id,
            'title' => $this->faker->sentence(),
            'subtitle' => $this->faker->sentence(),
            'content' => $this->faker->paragraph(),
        ];
    }
}

