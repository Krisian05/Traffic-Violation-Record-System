<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('violators', function (Blueprint $table) {
            // Photo
            $table->string('photo')->nullable()->after('license_number');

            // Extended personal info
            $table->string('place_of_birth')->nullable()->after('photo');
            $table->enum('civil_status', ['Single', 'Married', 'Widowed', 'Separated'])->nullable()->after('place_of_birth');
            $table->string('height')->nullable()->after('civil_status');
            $table->string('weight')->nullable()->after('height');
            $table->string('blood_type')->nullable()->after('weight');
            $table->string('valid_id')->nullable()->after('blood_type');
            $table->string('email')->nullable()->after('valid_id');

            // Split address into two
            $table->text('temporary_address')->nullable()->after('email');
            $table->text('permanent_address')->nullable()->after('temporary_address');

            // Family background
            $table->string('spouse_name')->nullable()->after('permanent_address');
            $table->text('children_names')->nullable()->after('spouse_name');
            $table->string('mothers_maiden_name')->nullable()->after('children_names');
            $table->string('father_name')->nullable()->after('mothers_maiden_name');

            // Educational background
            $table->string('elementary')->nullable()->after('father_name');
            $table->string('secondary')->nullable()->after('elementary');
            $table->string('vocation_course')->nullable()->after('secondary');
            $table->string('college')->nullable()->after('vocation_course');
            $table->string('graduate_school')->nullable()->after('college');
        });
    }

    public function down(): void
    {
        Schema::table('violators', function (Blueprint $table) {
            $table->dropColumn([
                'photo', 'place_of_birth', 'civil_status', 'height', 'weight',
                'blood_type', 'valid_id', 'email', 'temporary_address', 'permanent_address',
                'spouse_name', 'children_names', 'mothers_maiden_name', 'father_name',
                'elementary', 'secondary', 'vocation_course', 'college', 'graduate_school',
            ]);
        });
    }
};
