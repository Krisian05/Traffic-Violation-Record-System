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
            $table->foreignId('incident_charge_type_id')
                  ->nullable()
                  ->after('vehicle_photo')
                  ->constrained('incident_charge_types')
                  ->nullOnDelete();
            $table->dropForeign(['violation_type_id']);
            $table->dropColumn('violation_type_id');
        });
    }

    public function down(): void
    {
        Schema::table('incident_motorists', function (Blueprint $table) {
            $table->dropForeign(['incident_charge_type_id']);
            $table->dropColumn('incident_charge_type_id');
            $table->foreignId('violation_type_id')
                  ->nullable()
                  ->constrained('violation_types')
                  ->nullOnDelete();
        });
    }
};
