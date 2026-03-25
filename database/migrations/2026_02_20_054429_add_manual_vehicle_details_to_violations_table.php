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
        Schema::table('violations', function (Blueprint $table) {
            $table->string('vehicle_make',  100)->nullable()->after('vehicle_plate');
            $table->string('vehicle_color',  50)->nullable()->after('vehicle_make');
            $table->string('vehicle_or_number',  50)->nullable()->after('vehicle_color');
            $table->string('vehicle_cr_number',  50)->nullable()->after('vehicle_or_number');
            $table->string('vehicle_chassis', 100)->nullable()->after('vehicle_cr_number');
            $table->string('vehicle_photo',   255)->nullable()->after('vehicle_chassis');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('violations', function (Blueprint $table) {
            $table->dropColumn([
                'vehicle_make', 'vehicle_color',
                'vehicle_or_number', 'vehicle_cr_number',
                'vehicle_chassis', 'vehicle_photo',
            ]);
        });
    }
};
