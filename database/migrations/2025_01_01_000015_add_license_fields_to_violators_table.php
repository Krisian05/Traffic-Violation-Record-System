<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('violators', function (Blueprint $table) {
            $table->string('license_type')->nullable()->after('license_number');
            $table->string('license_restriction')->nullable()->after('license_type');
            $table->date('license_issued_date')->nullable()->after('license_restriction');
            $table->date('license_expiry_date')->nullable()->after('license_issued_date');
            $table->string('license_conditions')->nullable()->after('license_expiry_date');
        });
    }

    public function down(): void
    {
        Schema::table('violators', function (Blueprint $table) {
            $table->dropColumn([
                'license_type',
                'license_restriction',
                'license_issued_date',
                'license_expiry_date',
                'license_conditions',
            ]);
        });
    }
};
