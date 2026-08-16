<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $this->call([
            RolePermissionSeeder::class,
            UserSeeder::class,
            GenreSeeder::class,
            PlanSeeder::class,
            MovieSeeder::class,
            VideoAssetSeeder::class,
            SubscriptionSeeder::class,
            PaymentSeeder::class,
            RatingSeeder::class,
            WatchHistorySeeder::class,
        ]);
    }
}
