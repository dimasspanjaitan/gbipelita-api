<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('permissions_metas', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->string('module')->index();
            $table->string('menu')->nullable();
            $table->string('route_name')->unique();
            $table->string('permission_name')->unique();
            $table->string('action');
            $table->text('description')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('permissions_metas');
    }
};
