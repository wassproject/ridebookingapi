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
            // Modify existing
            $table->string('email')->nullable()->change();

            // Add new fields
            $table->string('middle_name')->nullable()->after('name');
            $table->string('last_name')->nullable()->after('middle_name');

            $table->string('address_line_2')->nullable()->after('address');
            $table->string('state')->after('address_line_2');
            $table->string('city')->after('state');
            $table->string('ward_or_area')->nullable()->after('city');
            $table->string('pin_code')->nullable()->after('ward_or_area');

            $table->date('dl_registration_date')->nullable()->after('dl_validity_date');
            $table->string('dl_permit')->after('dl_registration_date');

            $table->string('dl_photo')->nullable()->after('dl_permit');
            $table->string('aadhaar_front_photo')->nullable()->after('dl_photo');
            $table->string('aadhaar_back_photo')->nullable()->after('aadhaar_front_photo');
            $table->string('pan_card_photo')->nullable()->after('aadhaar_back_photo');
            $table->string('selfie_photo')->nullable()->after('pan_card_photo');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('drivers', function (Blueprint $table) {
            $table->dropColumn([
                'middle_name',
                'last_name',
                'address_line_2',
                'state',
                'city',
                'ward_or_area',
                'pin_code',
                'dl_registration_date',
                'dl_permit',
                'dl_photo',
                'aadhaar_front_photo',
                'aadhaar_back_photo',
                'pan_card_photo',
                'selfie_photo',
            ]);

            $table->string('email')->nullable(false)->change(); // revert email if needed
        });
    }
};
