<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Composite index for overdue/pendingActive scopes:
        //   WHERE status = 'pending' AND date_of_violation <= ?
        // Both columns queried together on every dashboard load.
        Schema::table('violations', function (Blueprint $table) {
            $table->index(['status', 'date_of_violation'], 'idx_violations_status_date');
        });

        // Composite index for violator profile page:
        //   WHERE violator_id = ? AND status = ?
        // Used when showing a violator's pending/settled violations.
        Schema::table('violations', function (Blueprint $table) {
            $table->index(['violator_id', 'status'], 'idx_violations_violator_status');
        });
    }

    public function down(): void
    {
        Schema::table('violations', function (Blueprint $table) {
            $table->dropIndex('idx_violations_status_date');
            $table->dropIndex('idx_violations_violator_status');
        });
    }
};
