<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('video_assets', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('movie_id');
            $table->string('quality', 10);           // 480p / 720p / 1080p / 4k
            $table->string('storage_key', 500);      // Supabase Storage object path, e.g. "movies/7/1080p.mp4"
            $table->string('format', 20)->default('mp4');
            $table->integer('size_mb')->nullable();

            $table->foreign('movie_id')->references('id')->on('movies')->cascadeOnDelete();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('video_assets');
    }
};
