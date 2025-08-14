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
        Schema::create('car_time_range', function (Blueprint $table) {
           $table->foreignId('car_id')->constrained('cars')->onDelete('cascade');
            $table->foreignId('time_range_id')->constrained('time_ranges')->onDelete('cascade');
            $table->primary(['car_id', 'time_range_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('car_time_range');
    }
};
