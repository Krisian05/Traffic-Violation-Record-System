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
        Schema::create('violation_vehicle_photos', function (Blueprint $table) {
            $table->id();
            $table->foreignId('violation_id')->constrained()->cascadeOnDelete();
            $table->string('photo');
            $table->timestamps();
        });

        Schema::table('violations', function (Blueprint $table) {
            $table->dropColumn('vehicle_photo');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('violation_vehicle_photos');

        Schema::table('violations', function (Blueprint $table) {
            $table->string('vehicle_photo', 255)->nullable()->after('vehicle_chassis');
        });
    }
};
