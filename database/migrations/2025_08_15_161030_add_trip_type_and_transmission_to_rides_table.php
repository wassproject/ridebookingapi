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
            $table->enum('trip_type', ['round_way', 'one_way'])->after('car_type_id');
            $table->enum('transmission', ['manual', 'automatic'])->after('trip_type');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('rides', function (Blueprint $table) {
            $table->dropColumn(['trip_type', 'transmission']);
        });
    }
};
