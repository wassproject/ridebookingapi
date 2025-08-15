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
        Schema::table('rides', function (Blueprint $table) {
            $table->date('trip_starting_date')->nullable()->after('car_type_id');
            $table->date('trip_ending_date')->nullable()->after('trip_starting_date');
            $table->time('time')->nullable()->after('trip_ending_date');
            $table->string('hourly')->nullable()->after('time');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('rides', function (Blueprint $table) {
            $table->dropColumn(['trip_starting_date', 'trip_ending_date', 'time', 'hourly']);
        });
    }
};
