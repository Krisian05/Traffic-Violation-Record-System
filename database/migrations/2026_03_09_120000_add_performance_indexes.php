<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // violators — frequently searched columns
        Schema::table('violators', function (Blueprint $table) {
            $table->index('license_number', 'idx_violators_license_number');
            $table->index('contact_number', 'idx_violators_contact_number');
            $table->index('last_name',      'idx_violators_last_name');
        });

        // violations — status-based queries run on every dashboard load
        Schema::table('violations', function (Blueprint $table) {
            $table->index('status',            'idx_violations_status');
            $table->index('date_of_violation', 'idx_violations_date');
            $table->index('ticket_number',     'idx_violations_ticket_number');
        });

        // incident_motorists — joined on every incident query
        Schema::table('incident_motorists', function (Blueprint $table) {
            $table->index('incident_id', 'idx_incident_motorists_incident');
            $table->index('violator_id', 'idx_incident_motorists_violator');
        });

        // vehicles — plate searches are common
        Schema::table('vehicles', function (Blueprint $table) {
            $table->index('plate_number', 'idx_vehicles_plate_number');
        });
    }

    public function down(): void
    {
        Schema::table('violators', function (Blueprint $table) {
            $table->dropIndex('idx_violators_license_number');
            $table->dropIndex('idx_violators_contact_number');
            $table->dropIndex('idx_violators_last_name');
        });

        Schema::table('violations', function (Blueprint $table) {
            $table->dropIndex('idx_violations_status');
            $table->dropIndex('idx_violations_date');
            $table->dropIndex('idx_violations_ticket_number');
        });

        Schema::table('incident_motorists', function (Blueprint $table) {
            $table->dropIndex('idx_incident_motorists_incident');
            $table->dropIndex('idx_incident_motorists_violator');
        });

        Schema::table('vehicles', function (Blueprint $table) {
            $table->dropIndex('idx_vehicles_plate_number');
        });
    }
};
