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
        $roles = ['guest', 'user', 'admin'];

        $navItems = [
            'home' => [
                'en' => 'Home',
                'ar' => 'الرئيسية',
                'route' => '/',
            ],
            'about' => [
                'en' => 'About',
                'ar' => 'حول',
                'route' => '/about'
            ],
            'dashboard' => [
                'en' => 'Dashboard',
                'ar' => 'لوحة التحكم',
                'route' => '/dashboard'
            ],
            'services' => [
                'en' => 'Services',
                'ar' => 'الخدمات',
                'route' => '/services'
            ],
            'blog' => [
                'en' => 'Blog',
                'ar' => 'المدونة',
                'route' => '/blog'
            ],
        ];

        foreach ($roles as $role) {
            // Navbar::where('group', $role)->delete();
            foreach ($navItems as $key => $navItem) {
                if($role != 'admin' && $key == 'dashboard') {
                    continue; // Skip dashboard for non-admin roles
                }
                $navbar = Navbar::create([
                    'link' => $navItem['route'],
                    'order' => 1, // You can set the order as needed
                    'group' => $role,
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
}
