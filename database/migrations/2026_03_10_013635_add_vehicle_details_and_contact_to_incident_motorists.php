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
            // Vehicle details for manual entry
            $table->string('vehicle_make')->nullable()->after('vehicle_type_manual');
            $table->string('vehicle_model')->nullable()->after('vehicle_make');
            $table->string('vehicle_color')->nullable()->after('vehicle_model');
            $table->string('vehicle_or_number')->nullable()->after('vehicle_color');
            $table->string('vehicle_cr_number')->nullable()->after('vehicle_or_number');
            $table->string('vehicle_chassis')->nullable()->after('vehicle_cr_number');
            // Contact info for unregistered motorists
            $table->string('motorist_contact')->nullable()->after('motorist_photo');
            $table->text('motorist_address')->nullable()->after('motorist_contact');
        });
    }

    public function down(): void
    {
        Schema::table('incident_motorists', function (Blueprint $table) {
            $table->dropColumn([
                'vehicle_make', 'vehicle_model', 'vehicle_color',
                'vehicle_or_number', 'vehicle_cr_number', 'vehicle_chassis',
                'motorist_contact', 'motorist_address',
            ]);
        });
    }
};
