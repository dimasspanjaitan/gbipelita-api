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
        // 1. Table Master Data: Departments
        Schema::create('departments', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->string('name')->unique();
            $table->string('alias')->unique()->nullable();
            $table->enum('status', ['active', 'inactive'])->default('active')->index();
            $table->text('content')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });

        // 2. Table Pivot: UserDepartment
        Schema::create('user_department', function (Blueprint $table) {
            $table->uuid('user_id');
            $table->uuid('department_id');

            // Composite Primary Key
            $table->primary(['user_id', 'department_id']);

            // Foreign Keys
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('department_id')->references('id')->on('departments')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('user_department');
        Schema::dropIfExists('departments');
    }
};
