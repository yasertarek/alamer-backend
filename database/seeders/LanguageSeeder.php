<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Language;

class LanguageSeeder extends Seeder
{
    public function run()
    {
        $languages = [
            ['code' => 'ar', 'name' => 'Arabic'],
            ['code' => 'en', 'name' => 'English'],
        ];

        foreach ($languages as $language) {
            Language::create($language);
        }
    }
}
