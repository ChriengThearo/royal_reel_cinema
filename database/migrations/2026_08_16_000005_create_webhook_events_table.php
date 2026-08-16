<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('webhook_events', function (Blueprint $table) {
            $table->increments('id');
            $table->string('stripe_event_id')->unique();          // idempotency key
            $table->string('type', 100);                          // e.g. invoice.paid
            $table->jsonb('payload');                             // full Stripe event payload
            $table->timestamp('processed_at')->nullable();
            $table->timestamp('created_at')->useCurrent();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('webhook_events');
    }
};
