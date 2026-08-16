<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('plans', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name', 100);
            $table->decimal('price', 10, 2);
            $table->string('currency', 10)->default('USD');
            $table->string('billing_cycle', 20)->default('monthly'); // monthly / yearly
            $table->string('max_quality', 10)->default('1080p');     // 480p/720p/1080p/4k
            $table->integer('screens_allowed')->default(1);
            $table->boolean('is_active')->default(true);

            // Stripe
            $table->string('stripe_product_id')->nullable(); // Stripe Product ID
            $table->string('stripe_price_id')->nullable();   // Stripe Price ID (used at checkout)

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('plans');
    }
};
