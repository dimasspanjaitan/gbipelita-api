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
        Schema::create('service_sessions', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->uuid('schedule_period_id')->index();
            $table->string('service_date');
            $table->unsignedTinyInteger('week_number');
            $table->unsignedTinyInteger('session_number');
            $table->string('start_time');
            $table->string('end_time');
            $table->timestamps();
            $table->softDeletes();

            $table->unique(
                [
                    'schedule_period_id',
                    'service_date',
                    'session_number'
                ],
                'ss_period_date_session_uq'
            );
        });

        Schema::create('service_requirements', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->uuid('service_session_id')->index();
            $table->uuid('division_id')->index();
            $table->uuid('skill_id')->index(); // piano, wl, camera, dll
            $table->unsignedTinyInteger('required_qty');
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('service_sessions');
        Schema::dropIfExists('service_requirements');
    }
};
