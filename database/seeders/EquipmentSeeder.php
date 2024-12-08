<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use App\Models\Equipment;

class EquipmentSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $equipments = [

            [
                'name' => 'Football',
                'description' => 'Standard size football used for matches and training.',
                'price_per_hour' => 10.00,
                'quantity_available' => 20,
                'condition' => 'Good',
                'deposit_amount' => 50.00,
                'image_path' => 'images/equipment/football.jpg',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Badminton Racket',
                'description' => 'Lightweight and durable badminton racket.',
                'price_per_hour' => 5.00,
                'quantity_available' => 15,
                'condition' => 'Good',
                'deposit_amount' => 20.00,
                'image_path' => 'images/equipment/badminton_racket.jpg',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Basketball',
                'description' => 'Professional basketball for indoor and outdoor use.',
                'price_per_hour' => 12.00,
                'quantity_available' => 10,
                'condition' => 'Good',
                'deposit_amount' => 60.00,
                'image_path' => 'images/equipment/basketball.jpg',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Tennis Racket',
                'description' => 'High-quality tennis racket for beginners and professionals.',
                'price_per_hour' => 8.00,
                'quantity_available' => 8,
                'condition' => 'Good',
                'deposit_amount' => 30.00,
                'image_path' => 'images/equipment/tennis_racket.jpg',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Cricket Bat',
                'description' => 'Premium cricket bat made of high-quality willow.',
                'price_per_hour' => 15.00,
                'quantity_available' => 5,
                'condition' => 'Good',
                'deposit_amount' => 75.00,
                'image_path' => 'images/equipment/cricket_bat.jpg',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Hockey Stick',
                'description' => 'Durable hockey stick for training and matches.',
                'price_per_hour' => 10.00,
                'quantity_available' => 12,
                'condition' => 'Good',
                'deposit_amount' => 40.00,
                'image_path' => 'images/equipment/hockey_stick.jpg',
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ];
        foreach ($equipments as $equipment) {
            Equipment::create($equipment);
        }
    }
}
