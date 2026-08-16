<?php

namespace Database\Seeders;

use App\Models\Payment;
use App\Models\Subscription;
use App\Models\User;
use Illuminate\Database\Seeder;

class PaymentSeeder extends Seeder
{
    public function run(): void
    {
        $rows = [
            ['email' => 'jane@example.com',  'amount' => 9.99],
            ['email' => 'john@example.com',  'amount' => 14.99],
        ];

        foreach ($rows as $row) {
            $user = User::where('email', $row['email'])->firstOrFail();

            // Pick the user's latest active subscription
            $subscription = Subscription::where('user_id', $user->id)
                ->where('status', 'active')
                ->firstOrFail();

            Payment::firstOrCreate(
                ['subscription_id' => $subscription->id],
                [
                    'amount'                   => $row['amount'],
                    'currency'                 => 'USD',
                    'method'                   => 'card',
                    'status'                   => 'paid',
                    'stripe_payment_intent_id' => null,
                    'stripe_invoice_id'        => null,
                    'paid_at'                  => now(),
                ]
            );
        }
    }
}
