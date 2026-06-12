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
        Schema::create('settings', function (Blueprint $table) {
            $table->id();
            $table->string('app_name', 100);
            $table->string('app_title', 70);
            $table->text('app_description');
            $table->string('app_logo', 255)->nullable();
            $table->string('app_logo_alternative', 255)->nullable();
            $table->string('app_favicon', 255)->nullable();
            $table->string('address', 255);
            $table->string('phone', 100);
            $table->string('email', 100)->nullable();
            $table->json('data')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('settings');
    }
};
