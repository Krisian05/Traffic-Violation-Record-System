<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Remove single photo column added earlier
        Schema::table('vehicles', function (Blueprint $table) {
            $table->dropColumn('photo');
        });

        // Create dedicated multi-photo table
        Schema::create('vehicle_photos', function (Blueprint $table) {
            $table->id();
            $table->foreignId('vehicle_id')->constrained()->cascadeOnDelete();
            $table->string('photo');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('vehicle_photos');
        Schema::table('vehicles', function (Blueprint $table) {
            $table->string('photo')->nullable()->after('cr_number');
        });
    }
};
