<?php

namespace Database\Seeders;

use App\Models\Permission;
use App\Models\Role;
use Illuminate\Database\Seeder;

class PermissionSeeder extends Seeder
{
    public function run(): void
    {
        $permissions = [
            ['code' => 'movies.create',        'name' => 'Create Movies',        'module' => 'movies'],
            ['code' => 'movies.edit',          'name' => 'Edit Movies',          'module' => 'movies'],
            ['code' => 'movies.delete',        'name' => 'Delete Movies',        'module' => 'movies'],
            ['code' => 'movies.publish',       'name' => 'Publish Movies',       'module' => 'movies'],
            ['code' => 'genres.manage',        'name' => 'Manage Genres',        'module' => 'genres'],
            ['code' => 'plans.manage',         'name' => 'Manage Plans',         'module' => 'plans'],
            ['code' => 'users.manage',         'name' => 'Manage Users',         'module' => 'users'],
            ['code' => 'subscriptions.manage', 'name' => 'Manage Subscriptions', 'module' => 'subscriptions'],
            ['code' => 'payments.view',        'name' => 'View Payments',        'module' => 'payments'],
        ];

        $permissionIds = [];
        foreach ($permissions as $data) {
            $p = Permission::firstOrCreate(['code' => $data['code']], [
                'name'   => $data['name'],
                'module' => $data['module'],
            ]);
            $permissionIds[] = $p->id;
        }

        // Attach all permissions to the admin role
        $admin = Role::where('name', 'admin')->first();
        if ($admin) {
            $admin->permissions()->syncWithoutDetaching($permissionIds);
        }
    }
}
