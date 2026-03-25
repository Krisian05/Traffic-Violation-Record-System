<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('violations', function (Blueprint $table) {
            $table->string('or_number')->nullable()->after('status');
            $table->string('cashier_name')->nullable()->after('or_number');
            $table->string('receipt_photo')->nullable()->after('cashier_name');
        });
    }

    public function down(): void
    {
        Schema::table('violations', function (Blueprint $table) {
            $table->dropColumn(['or_number', 'cashier_name', 'receipt_photo']);
        });
    }
};
