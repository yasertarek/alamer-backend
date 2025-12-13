<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Phone;

class PhoneSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        //
        Phone::create([
            'number' => '+966507228651',
            'type' => 'mobile',
            'is_active' => true,
        ]);
        Phone::create([
            'number' => '+966507228651',
            'type' => 'whatsapp',
            'is_active' => true,
        ]);
    }
}
