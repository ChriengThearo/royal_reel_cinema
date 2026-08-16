<?php

namespace Database\Seeders;

use App\Models\Role;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        $adminRole = Role::where('name', 'admin')->firstOrFail();
        $userRole  = Role::where('name', 'user')->firstOrFail();

        $users = [
            ['name' => 'Admin User',   'email' => 'admin@example.com', 'role' => $adminRole],
            ['name' => 'Jane Doe',     'email' => 'jane@example.com',  'role' => $userRole],
            ['name' => 'John Smith',   'email' => 'john@example.com',  'role' => $userRole],
            ['name' => 'Alice Nguyen', 'email' => 'alice@example.com', 'role' => $userRole],
        ];

        foreach ($users as $data) {
            $user = User::firstOrCreate(
                ['email' => $data['email']],
                [
                    'name'              => $data['name'],
                    'password'          => Hash::make('password'),
                    'email_verified_at' => now(),
                ]
            );

            $user->roles()->syncWithoutDetaching([$data['role']->id]);
        }
    }
}
