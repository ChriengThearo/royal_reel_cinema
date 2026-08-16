<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('subscriptions', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedBigInteger('user_id');
            $table->unsignedInteger('plan_id');

            // Normalized local status: active / expired / cancelled / pending
            $table->string('status', 20)->default('active');

            $table->timestamp('start_date')->useCurrent();
            $table->timestamp('end_date');
            $table->boolean('auto_renew')->default(true);

            // Stripe — source of truth, kept in sync via webhooks
            $table->string('stripe_subscription_id')->nullable()->unique();
            $table->string('stripe_status', 50)->nullable(); // active/trialing/past_due/canceled/incomplete
            $table->boolean('cancel_at_period_end')->default(false);

            $table->timestamps();

            $table->foreign('user_id')->references('id')->on('users')->cascadeOnDelete();
            $table->foreign('plan_id')->references('id')->on('plans');

            $table->index(['user_id', 'status'], 'idx_subscriptions_user_status');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('subscriptions');
    }
};
