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
        Schema::table('violators', function (Blueprint $table) {
            $table->dropColumn([
                'spouse_name',
                'children_names',
                'mothers_maiden_name',
                'father_name',
                'elementary',
                'secondary',
                'vocation_course',
                'college',
                'graduate_school',
            ]);
        });
    }

    public function down(): void
    {
        Schema::table('violators', function (Blueprint $table) {
            $table->string('spouse_name')->nullable();
            $table->text('children_names')->nullable();
            $table->string('mothers_maiden_name')->nullable();
            $table->string('father_name')->nullable();
            $table->string('elementary')->nullable();
            $table->string('secondary')->nullable();
            $table->string('vocation_course')->nullable();
            $table->string('college')->nullable();
            $table->string('graduate_school')->nullable();
        });
    }
};
