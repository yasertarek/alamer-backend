<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Cats;
class CatsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        //
        Cats::create(['name' => 'عزل الأسطح']);
        Cats::create(['name' => 'عزل حراري']);
        Cats::create(['name' => 'عزل الخزانات']);
        Cats::create(['name' => 'كشف تسربات المياه']);
        Cats::create(['name' => 'العزل الحديث']);
    }
}
