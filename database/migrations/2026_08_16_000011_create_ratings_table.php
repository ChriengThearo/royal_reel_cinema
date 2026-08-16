<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('ratings', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedBigInteger('user_id');
            $table->unsignedInteger('movie_id');
            $table->smallInteger('score'); // 1–10, enforced at app level (DB CHECK not in Blueprint)
            $table->timestamp('created_at')->useCurrent();

            $table->foreign('user_id')->references('id')->on('users')->cascadeOnDelete();
            $table->foreign('movie_id')->references('id')->on('movies')->cascadeOnDelete();

            $table->unique(['user_id', 'movie_id']); // one rating per user per movie
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('ratings');
    }
};
