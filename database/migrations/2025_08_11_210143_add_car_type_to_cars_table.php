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
        Schema::table('cars', function (Blueprint $table) {
                $table->foreignId('car_type_id')->nullable()->after('id')->constrained('car_types')->nullOnDelete();
            // ensure transmission column already exists; if not:
            if (!Schema::hasColumn('cars', 'transmission')) {
                $table->enum('transmission', ['manual', 'auto'])->nullable()->after('description');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('cars', function (Blueprint $table) {
              $table->dropForeign(['car_type_id']);
            $table->dropColumn('car_type_id');
        });
    }
};
