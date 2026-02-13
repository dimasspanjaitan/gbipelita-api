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
        Schema::create('schedule_periods', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->uuid('department_id')->index();
            $table->unsignedTinyInteger('month');
            $table->unsignedSmallInteger('year');
            $table->unsignedTinyInteger('max_service_per_week')->nullable()->default(2);
            $table->enum('status', [
                'draft',
                'open',
                'closed',
                'generating',
                'generated',
                'published',
                'failed',
            ])->default('draft');
            $table->timestamps();
            $table->softDeletes();

            $table->unique(['department_id', 'month', 'year']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('schedule_periods');
    }
};
