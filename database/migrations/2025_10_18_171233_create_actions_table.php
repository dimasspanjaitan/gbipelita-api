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
        Schema::create('actions', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->uuid('module_id')->index();
            $table->boolean('is_default_action')->default(false);
            $table->string('name');
            $table->string('label');
            $table->string('permission_name')->unique();
            $table->integer('order')->default(0);
            $table->timestamps();
            $table->softDeletes();

            $table->unique(['module_id', 'name']);
            $table->unique(['module_id', 'label']);

            $table->foreign('module_id')
                ->references('id')
                ->on('modules')
                ->cascadeOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('actions');
    }
};
