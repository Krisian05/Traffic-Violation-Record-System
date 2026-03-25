<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\ViolationType;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // Bootstrap operator account
        User::updateOrCreate(
            ['username' => 'admin'],
            [
                'name'     => 'Administrator',
                'role'     => 'operator',
                'password' => Hash::make(env('DEFAULT_ADMIN_PASSWORD', 'admin123')),
            ]
        );

        // Seed common traffic violation types
        $types = [
            ['name' => 'No Helmet',             'description' => 'Riding without a helmet.',                      'fine_amount' => 1500.00],
            ['name' => 'No Driver\'s License',  'description' => 'Operating a vehicle without a valid license.',  'fine_amount' => 3000.00],
            ['name' => 'Reckless Driving',       'description' => 'Operating a vehicle in a reckless manner.',    'fine_amount' => 2000.00],
            ['name' => 'Illegal Parking',        'description' => 'Parking in a prohibited zone or area.',        'fine_amount' => 1000.00],
            ['name' => 'Speeding',               'description' => 'Exceeding the posted speed limit.',            'fine_amount' => 2000.00],
            ['name' => 'Overloading',            'description' => 'Exceeding the maximum load capacity.',         'fine_amount' => 5000.00],
            ['name' => 'No Registration',        'description' => 'Operating a vehicle without valid registration.', 'fine_amount' => 3000.00],
            ['name' => 'Beating Red Light',      'description' => 'Passing through a red traffic signal.',        'fine_amount' => 1500.00],
            ['name' => 'No Seatbelt',            'description' => 'Failure to wear a seatbelt while driving.',    'fine_amount' => 1000.00],
            ['name' => 'Counterflow',            'description' => 'Driving against the flow of traffic.',         'fine_amount' => 1500.00],
            ['name' => 'Drunk Driving',          'description' => 'Operating a vehicle under the influence of alcohol.', 'fine_amount' => 5000.00],
            ['name' => 'Obstruction',            'description' => 'Blocking traffic or road access.',             'fine_amount' => 1000.00],
        ];

        foreach ($types as $type) {
            ViolationType::create($type);
        }
    }
}
