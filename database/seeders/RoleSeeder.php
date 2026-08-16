<?php

namespace Database\Seeders;

use App\Models\Role;
use Illuminate\Database\Seeder;

class RoleSeeder extends Seeder
{
    public function run(): void
    {
        Role::firstOrCreate(['name' => 'admin'], ['description' => 'Full system access']);
        Role::firstOrCreate(['name' => 'user'],  ['description' => 'Standard viewer account']);
    }
}
