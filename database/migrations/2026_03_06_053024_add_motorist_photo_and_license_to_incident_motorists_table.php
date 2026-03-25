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
            $table->string('motorist_photo')->nullable()->after('motorist_license');
            $table->string('license_type')->nullable()->after('motorist_photo');
            $table->string('license_restriction')->nullable()->after('license_type');
            $table->date('license_expiry_date')->nullable()->after('license_restriction');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('incident_motorists', function (Blueprint $table) {
            $table->dropColumn(['motorist_photo', 'license_type', 'license_restriction', 'license_expiry_date']);
        });
    }
};
