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
        Schema::create('cars', function (Blueprint $table) {
         $table->id();
            $table->unsignedBigInteger('driver_id'); // Relation to driver
            $table->string('type'); // SUV, Hatchback, Sedan
            $table->text('description')->nullable();
            $table->enum('transmission', ['manual', 'auto']);
            $table->time('available_at')->nullable(); // availability for booking
            $table->timestamps();

            $table->foreign('driver_id')->references('id')->on('drivers')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('cars');
    }
};
