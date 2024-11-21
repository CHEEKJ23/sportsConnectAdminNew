<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Court;

class CourtSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $courts = [
            // Courts for Sport Center 1
            ['sport_center_id' => 1, 'number' => 1, 'type' => 'Badminton'],
            ['sport_center_id' => 1, 'number' => 2, 'type' => 'Tennis'],
            ['sport_center_id' => 1, 'number' => 3, 'type' => 'Basketball'],

            // Courts for Sport Center 2
            ['sport_center_id' => 2, 'number' => 1, 'type' => 'Badminton'],
            ['sport_center_id' => 2, 'number' => 2, 'type' => 'Futsal'],
            ['sport_center_id' => 2, 'number' => 3, 'type' => 'Tennis'],

            // Courts for Sport Center 3
            ['sport_center_id' => 3, 'number' => 1, 'type' => 'Basketball'],
            ['sport_center_id' => 3, 'number' => 2, 'type' => 'Tennis'],
            ['sport_center_id' => 3, 'number' => 3, 'type' => 'Volleyball'],
        ];

        foreach ($courts as $court) {
            Court::create($court);
        }
    }
}
