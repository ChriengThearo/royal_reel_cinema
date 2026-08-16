<?php

namespace Database\Seeders;

use App\Models\Plan;
use Illuminate\Database\Seeder;

class PlanSeeder extends Seeder
{
    public function run(): void
    {
        $plans = [
            [
                'name'             => 'Basic',
                'price'            => 4.99,
                'currency'         => 'USD',
                'billing_cycle'    => 'monthly',
                'max_quality'      => '720p',
                'screens_allowed'  => 1,
                'is_active'        => true,
                'stripe_product_id'=> null,
                'stripe_price_id'  => null,
            ],
            [
                'name'             => 'Standard',
                'price'            => 9.99,
                'currency'         => 'USD',
                'billing_cycle'    => 'monthly',
                'max_quality'      => '1080p',
                'screens_allowed'  => 2,
                'is_active'        => true,
                'stripe_product_id'=> null,
                'stripe_price_id'  => null,
            ],
            [
                'name'             => 'Premium',
                'price'            => 14.99,
                'currency'         => 'USD',
                'billing_cycle'    => 'monthly',
                'max_quality'      => '4k',
                'screens_allowed'  => 4,
                'is_active'        => true,
                'stripe_product_id'=> null,
                'stripe_price_id'  => null,
            ],
        ];

        foreach ($plans as $data) {
            Plan::firstOrCreate(['name' => $data['name']], $data);
        }
    }
}
