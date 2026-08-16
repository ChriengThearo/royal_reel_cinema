<?php

namespace App\Console\Commands;

use App\Models\Role;
use App\Models\User;
use Illuminate\Console\Command;

class MakeAdminCommand extends Command
{
    protected $signature   = 'user:make-admin {email : The email address of the user to promote}';
    protected $description = 'Promote an existing user to the admin role';

    public function handle(): int
    {
        $email = $this->argument('email');

        $user = User::where('email', $email)->first();
        if (! $user) {
            $this->error("No user found with email: {$email}");
            return self::FAILURE;
        }

        $adminRole = Role::where('name', 'admin')->first();
        if (! $adminRole) {
            $this->error('Admin role not found. Run: php artisan db:seed --class=RoleSeeder');
            return self::FAILURE;
        }

        $user->roles()->syncWithoutDetaching([$adminRole->id]);

        $this->info("✓ {$user->name} ({$email}) has been promoted to admin.");
        return self::SUCCESS;
    }
}
