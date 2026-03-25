<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        $isMysql = DB::getDriverName() === 'mysql';

        if ($isMysql) {
            // Step 1: Expand enum to include both old and new value
            DB::statement("ALTER TABLE users MODIFY COLUMN role ENUM('operator','viewer','traffic_officer') NOT NULL DEFAULT 'traffic_officer'");
        }

        // Step 2: Migrate data (works on all drivers)
        DB::table('users')->where('role', 'viewer')->update(['role' => 'traffic_officer']);

        if ($isMysql) {
            // Step 3: Remove old 'viewer' value from enum
            DB::statement("ALTER TABLE users MODIFY COLUMN role ENUM('operator','traffic_officer') NOT NULL DEFAULT 'traffic_officer'");
        }
    }

    public function down(): void
    {
        $isMysql = DB::getDriverName() === 'mysql';

        if ($isMysql) {
            DB::statement("ALTER TABLE users MODIFY COLUMN role ENUM('operator','viewer','traffic_officer') NOT NULL DEFAULT 'viewer'");
        }

        DB::table('users')->where('role', 'traffic_officer')->update(['role' => 'viewer']);

        if ($isMysql) {
            DB::statement("ALTER TABLE users MODIFY COLUMN role ENUM('operator','viewer') NOT NULL DEFAULT 'viewer'");
        }
    }
};
