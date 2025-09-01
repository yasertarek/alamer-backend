<?php

namespace Database\Seeders;

use App\Models\NavbarTranslation;
use Illuminate\Database\Seeder;
use App\Models\Navbar;
use App\Models\Language;

class NavbarSeeder extends Seeder
{
    public function run()
    {
        // Assume these are the languages you've defined
        $languages = Language::all();

        $navItems = [
            'home' => [
                'en' => 'Home',
                'ar' => 'الرئيسية',
            ],
            'about' => [
                'en' => 'About',
                'ar' => 'حول',
            ],
            'contact' => [
                'en' => 'Contact',
                'ar' => 'اتصل',
            ],
        ];

        foreach ($navItems as $key => $navItem) {
            $navbar = Navbar::create([
                'link' => '/' . $key,  // Assuming the link follows the same key pattern
                'order' => 1, // You can set the order as needed
            ]);
            foreach ($languages as $language) {
                $title = $navItem[$language->code] ?? 'Undefined';
                NavbarTranslation::create([
                    'title' => $title,
                    'language_id' => $language->id,
                    'navbar_id' => $navbar->id,
                ]);

            }
        }
    }
}
