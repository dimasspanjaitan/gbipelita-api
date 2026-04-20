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
        Schema::create('schedule_user_period_statuses', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->uuid('schedule_period_id')->index();
            $table->uuid('user_id')->index();
            $table->boolean('has_submitted')->default(false);
            $table->timestamps();
            $table->softDeletes();

            $table->unique([
                'schedule_period_id',
                'user_id'
            ]);

            $table->foreign('schedule_period_id')->references('id')->on('schedule_periods')->onDelete('cascade');
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('schedule_user_period_statuses');
    }
};
