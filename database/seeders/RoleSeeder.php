<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;

class RoleSeeder extends Seeder
{
    public function run()
    {
        Role::create(['name' => 'supervisor', 'guard_name' => 'admin']);
        Role::create(['name' => 'moderator', 'guard_name' => 'admin']);
    }
}
