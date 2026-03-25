<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('vehicles', function (Blueprint $table) {
            $table->id();
            $table->foreignId('violator_id')->constrained()->cascadeOnDelete();
            $table->string('plate_number')->unique();
            $table->enum('vehicle_type', ['MV', 'MC']);
            $table->string('make')->nullable();
            $table->string('model')->nullable();
            $table->string('color')->nullable();
            $table->smallInteger('year')->nullable();
            $table->string('or_number')->nullable();
            $table->string('cr_number')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('vehicles');
    }
};
