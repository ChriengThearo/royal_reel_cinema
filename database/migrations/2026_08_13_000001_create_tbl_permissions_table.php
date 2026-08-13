<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('tbl_permissions', function (Blueprint $table) {
            $table->integer('id')->autoIncrement();
            $table->string('code', 64);
            $table->string('name', 120);
            $table->string('description', 255)->nullable();
            $table->string('module', 80)->nullable();
            $table->text('aliases')->nullable();

            $table->unique('code', 'ix_tbl_permissions_code');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('tbl_permissions');
    }
};
