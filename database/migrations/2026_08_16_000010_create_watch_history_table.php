<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('watch_history', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedBigInteger('user_id');
            $table->unsignedInteger('movie_id');
            $table->integer('progress_seconds')->default(0);
            $table->boolean('completed')->default(false);
            $table->timestamp('last_watched_at')->useCurrent();

            $table->foreign('user_id')->references('id')->on('users')->cascadeOnDelete();
            $table->foreign('movie_id')->references('id')->on('movies')->cascadeOnDelete();

            $table->index(['user_id', 'movie_id'], 'idx_watch_history_user_movie');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('watch_history');
    }
};
