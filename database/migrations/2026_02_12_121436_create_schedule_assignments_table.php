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
        Schema::create('schedule_assignments', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->uuid('schedule_period_id')->index();
            $table->uuid('service_session_id')->index();
            $table->uuid('service_requirement_id')->index();
            $table->uuid('user_id')->index();
            $table->boolean('is_system_generated')->default(true);
            $table->timestamps();
            $table->softDeletes();

            $table->unique([
                'service_requirement_id',
                'user_id',
            ]);

            $table->foreign('schedule_period_id')->references('id')->on('schedule_periods')->onDelete('cascade');
            $table->foreign('service_session_id')->references('id')->on('service_sessions')->onDelete('cascade');
            $table->foreign('service_requirement_id')->references('id')->on('service_requirements')->onDelete('cascade');
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('schedule_assignments');
    }
};
