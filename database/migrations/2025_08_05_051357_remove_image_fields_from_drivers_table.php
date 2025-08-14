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
            $table->dropColumn([
                'user_photos',
                'dl_photo',
                'aadhaar_front_photo',
                'aadhaar_back_photo',
                'pan_card_photo',
                'selfie_photo',
            ]);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('drivers', function (Blueprint $table) {
            $table->string('user_photos')->nullable();
            $table->string('dl_photo')->nullable();
            $table->string('aadhaar_front_photo')->nullable();
            $table->string('aadhaar_back_photo')->nullable();
            $table->string('pan_card_photo')->nullable();
            $table->string('selfie_photo')->nullable();
        });
    }
};
