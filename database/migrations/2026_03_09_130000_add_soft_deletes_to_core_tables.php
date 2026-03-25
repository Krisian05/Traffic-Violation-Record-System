<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('violators', function (Blueprint $table) {
            $table->softDeletes();
        });

        Schema::table('violations', function (Blueprint $table) {
            $table->softDeletes();
        });

        Schema::table('vehicles', function (Blueprint $table) {
            $table->softDeletes();
        });

        Schema::table('incidents', function (Blueprint $table) {
            $table->softDeletes();
        });
    }

    public function down(): void
    {
        Schema::table('violators',  fn (Blueprint $t) => $t->dropSoftDeletes());
        Schema::table('violations', fn (Blueprint $t) => $t->dropSoftDeletes());
        Schema::table('vehicles',   fn (Blueprint $t) => $t->dropSoftDeletes());
        Schema::table('incidents',  fn (Blueprint $t) => $t->dropSoftDeletes());
    }
};
