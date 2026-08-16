<?php

namespace Database\Seeders;

use App\Models\Plan;
use App\Models\Subscription;
use App\Models\User;
use Illuminate\Database\Seeder;

class SubscriptionSeeder extends Seeder
{
    public function run(): void
    {
        $rows = [
            ['email' => 'jane@example.com',  'plan' => 'Standard'],
            ['email' => 'john@example.com',  'plan' => 'Premium'],
            // Alice Nguyen intentionally has NO subscription (tests the paywall).
        ];

        foreach ($rows as $row) {
            $user = User::where('email', $row['email'])->firstOrFail();
            $plan = Plan::where('name', $row['plan'])->firstOrFail();

            Subscription::firstOrCreate(
                ['user_id' => $user->id, 'plan_id' => $plan->id],
                [
                    'status'                 => 'active',
                    'start_date'             => now(),
                    'end_date'               => now()->addMonth(),
                    'auto_renew'             => true,
                    'stripe_subscription_id' => null,
                    'stripe_status'          => null,
                    'cancel_at_period_end'   => false,
                ]
            );
        }
    }
}
