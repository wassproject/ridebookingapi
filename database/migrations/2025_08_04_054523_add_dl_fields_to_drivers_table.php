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
        Schema::table('drivers', function (Blueprint $table) {
            $table->string('dl_number')->after('pan_number');
            $table->date('dl_validity_date')->after('dl_number');
            $table->json('user_photos')->nullable()->after('dl_validity_date');
            $table->boolean('declaration_form')->default(false)->after('user_photos');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('drivers', function (Blueprint $table) {
            $table->dropColumn(['dl_number', 'dl_validity_date', 'user_photos', 'declaration_form']);
        });
    }
};
