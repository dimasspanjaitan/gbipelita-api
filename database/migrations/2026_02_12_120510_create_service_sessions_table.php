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
            $table->string('name');
            $table->string('time');
            $table->uuid('schedule_period_id')->index();
            $table->date('service_date');
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

            $table->foreign('schedule_period_id')->references('id')->on('schedule_periods')->onDelete('cascade');
        });

        Schema::create('service_requirements', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->uuid('service_session_id')->index();
            $table->uuid('division_id')->index();
            $table->uuid('skill_id')->index(); // piano, wl, camera, dll
            $table->unsignedTinyInteger('required_qty');
            $table->timestamps();
            $table->softDeletes();

            $table->unique([
                'service_session_id',
                'skill_id'
            ], 'sr_session_skill_uq');

            $table->foreign('service_session_id')->references('id')->on('service_sessions')->onDelete('cascade');
            $table->foreign('division_id')->references('id')->on('divisions')->onDelete('cascade');
            $table->foreign('skill_id')->references('id')->on('skills')->onDelete('cascade');
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
