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
            $table->date('dl_registration_date')->nullable(false)->change();
            $table->string('dl_photo')->nullable(false)->change();
            $table->string('aadhaar_front_photo')->nullable(false)->change();
            $table->string('aadhaar_back_photo')->nullable(false)->change();
            $table->string('pan_card_photo')->nullable(false)->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('drivers', function (Blueprint $table) {
            $table->date('dl_registration_date')->nullable()->change();
            $table->string('dl_photo')->nullable()->change();
            $table->string('aadhaar_front_photo')->nullable()->change();
            $table->string('aadhaar_back_photo')->nullable()->change();
            $table->string('pan_card_photo')->nullable()->change();
        });
    }
};
