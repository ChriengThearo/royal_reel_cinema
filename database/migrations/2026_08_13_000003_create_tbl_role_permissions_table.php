<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('tbl_role_permissions', function (Blueprint $table) {
            $table->integer('role_id');
            $table->integer('permission_id');

            $table->primary(['role_id', 'permission_id']);
            $table->index('permission_id');

            $table->foreign('role_id')->references('id')->on('tbl_roles')->cascadeOnDelete();
            $table->foreign('permission_id')->references('id')->on('tbl_permissions')->cascadeOnDelete();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('tbl_role_permissions');
    }
};
