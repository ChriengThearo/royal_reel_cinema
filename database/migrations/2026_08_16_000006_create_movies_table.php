<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('movies', function (Blueprint $table) {
            $table->increments('id');
            $table->string('title');
            $table->string('slug')->unique();
            $table->text('description')->nullable();
            $table->date('release_date')->nullable();
            $table->integer('duration_minutes')->nullable();
            $table->string('poster_url', 500)->nullable();
            $table->string('backdrop_url', 500)->nullable();
            $table->string('trailer_url', 500)->nullable();
            $table->string('age_rating', 10)->nullable();          // G / PG / PG-13 / R
            $table->string('status', 20)->default('draft');        // draft / published / archived

            // Access control
            $table->string('access_type', 20)->default('free');    // free / subscription
            // NULL = any active plan; set = only this specific plan
            $table->unsignedInteger('required_plan_id')->nullable();
            $table->unsignedBigInteger('created_by')->nullable();

            $table->timestamps();

            $table->foreign('required_plan_id')->references('id')->on('plans')->nullOnDelete();
            $table->foreign('created_by')->references('id')->on('users')->nullOnDelete();

            $table->index('access_type', 'idx_movies_access_type');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('movies');
    }
};
