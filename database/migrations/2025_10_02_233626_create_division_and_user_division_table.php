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
        // 1. Table Master Data: Divisions
        Schema::create('divisions', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->string('name')->unique();
            $table->string('alias')->unique()->nullable();
            $table->uuid('department_id');
            $table->enum('status', ['active', 'inactive'])->default('active')->index();
            $table->text('content')->nullable();
            $table->timestamps();
            $table->softDeletes();

            // Unique key
            $table->unique(['name', 'department_id']);

            // Foreign Key
            $table->foreign('department_id')->references('id')->on('departments')->onDelete('cascade');
        });

        // 2. Tabel Pivot: UserDivision
        Schema::create('user_division', function (Blueprint $table) {
            $table->uuid('user_id');
            $table->uuid('division_id');
            $table->integer('priority')->default(1);

            // Composite Primary Key
            $table->primary(['user_id', 'division_id']);

            // Foreign Keys
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('division_id')->references('id')->on('divisions')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('user_division');
        Schema::dropIfExists('divisions');
    }
};
