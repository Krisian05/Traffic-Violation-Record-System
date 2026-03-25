<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('incident_charge_types', function (Blueprint $table) {
            $table->id();
            $table->string('name')->unique();
            $table->text('description')->nullable();
            $table->timestamps();
        });

        // Seed standard RIR charge types (Art. 365, Revised Penal Code)
        $now = now();
        DB::table('incident_charge_types')->insert([
            ['name' => 'Reckless Imprudence Resulting in Homicide',                                 'description' => 'Art. 365, Revised Penal Code', 'created_at' => $now, 'updated_at' => $now],
            ['name' => 'Reckless Imprudence Resulting in Physical Injuries',                        'description' => 'Art. 365, Revised Penal Code', 'created_at' => $now, 'updated_at' => $now],
            ['name' => 'Reckless Imprudence Resulting in Damage to Property',                       'description' => 'Art. 365, Revised Penal Code', 'created_at' => $now, 'updated_at' => $now],
            ['name' => 'Reckless Imprudence Resulting in Homicide and Physical Injuries',           'description' => 'Art. 365, Revised Penal Code', 'created_at' => $now, 'updated_at' => $now],
            ['name' => 'Reckless Imprudence Resulting in Homicide and Damage to Property',          'description' => 'Art. 365, Revised Penal Code', 'created_at' => $now, 'updated_at' => $now],
            ['name' => 'Reckless Imprudence Resulting in Physical Injuries and Damage to Property', 'description' => 'Art. 365, Revised Penal Code', 'created_at' => $now, 'updated_at' => $now],
            ['name' => 'Reckless Imprudence Resulting in Multiple Homicide',                        'description' => 'Art. 365, Revised Penal Code', 'created_at' => $now, 'updated_at' => $now],
            ['name' => 'Hit and Run',                                                               'description' => null,                          'created_at' => $now, 'updated_at' => $now],
        ]);
    }

    public function down(): void
    {
        Schema::dropIfExists('incident_charge_types');
    }
};
