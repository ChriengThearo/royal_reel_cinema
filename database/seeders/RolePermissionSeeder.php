<?php

namespace Database\Seeders;

use App\Models\Permission;
use App\Models\Role;
use Illuminate\Database\Seeder;

class RolePermissionSeeder extends Seeder
{
    public function run(): void
    {
        // ── Roles ─────────────────────────────────────────────────────────────
        $admin = Role::firstOrCreate(['name' => 'admin'], ['description' => 'Full system access']);
        Role::firstOrCreate(['name' => 'user'],  ['description' => 'Standard viewer account']);

        // ── Permissions ───────────────────────────────────────────────────────
        $permissionCodes = [
            'movies.create',
            'movies.edit',
            'movies.delete',
            'movies.publish',
            'genres.manage',
            'plans.manage',
            'users.manage',
            'subscriptions.manage',
            'payments.view',
        ];

        $permissionIds = [];
        foreach ($permissionCodes as $code) {
            $p = Permission::firstOrCreate(['code' => $code], [
                'name'   => ucwords(str_replace(['.', '_'], ' ', $code)),
                'module' => explode('.', $code)[0],
            ]);
            $permissionIds[] = $p->id;
        }

        // Attach ALL permissions to admin role only
        $admin->permissions()->syncWithoutDetaching($permissionIds);
    }
}
