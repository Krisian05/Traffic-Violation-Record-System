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
        Schema::table('incident_motorists', function (Blueprint $table) {
            $table->foreignId('vehicle_id')->nullable()->after('violator_id')->constrained('vehicles')->nullOnDelete();
            $table->string('vehicle_plate')->nullable()->after('vehicle_id');
            $table->string('vehicle_type_manual')->nullable()->after('vehicle_plate');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('incident_motorists', function (Blueprint $table) {
            $table->dropForeign(['vehicle_id']);
            $table->dropColumn(['vehicle_id', 'vehicle_plate', 'vehicle_type_manual']);
        });
    }
};
