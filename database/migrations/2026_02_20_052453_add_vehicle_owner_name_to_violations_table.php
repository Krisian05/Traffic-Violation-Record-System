<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('violations', function (Blueprint $table) {
            // Stores the registered owner name when the driver borrowed the vehicle
            $table->string('vehicle_owner_name', 200)->nullable()->after('vehicle_id');
        });
    }

    public function down(): void
    {
        Schema::table('violations', function (Blueprint $table) {
            $table->dropColumn('vehicle_owner_name');
        });
    }
};
