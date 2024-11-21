<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\SportCenter;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class SportCenterSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $sportCenters = [
            [
                'name' => 'Sports Arena A',
                'description' => 'A premium sports arena with multiple courts.',
                'location' => '123 Main Street, City A',
                'image' => 'sports_arena_a.jpg',
                'price' => 100.00,
            ],
            [
                'name' => 'City Sports Hall',
                'description' => 'A community sports hall with modern facilities.',
                'location' => '456 Market Street, City B',
                'image' => 'city_sports_hall.jpg',
                'price' => 80.00,
            ],
            [
                'name' => 'Elite Sports Complex',
                'description' => 'An elite sports complex for professionals.',
                'location' => '789 Elite Avenue, City C',
                'image' => 'elite_sports_complex.jpg',
                'price' => 150.00,
            ],
        ];

        foreach ($sportCenters as $center) {
            SportCenter::create($center);
        }
    }
}
