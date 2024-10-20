<?php

namespace Database\Seeders\Anthaleja\MegaWareHouse;

use Faker\Factory as Faker;
use Illuminate\Database\Seeder;
use App\Models\Anthaleja\MegaWareHouse\Drone;
use App\Models\Anthaleja\MegaWareHouse\Warehouse;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class TransportSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $faker = Faker::create();

        // Droni
        $drones = 12;
        $robots = 12;
        for ($d = 1; $d <= $drones; $d++) {
            Drone::create([
                'type' => $type = $faker->randomElement(['delivery', 'surveillance']),
                'battery_life' => $faker->numberBetween(14, 28),
                'capacity' => $type == 'surveillance' ? 0 : $faker->numberBetween(5, 10) * 10,
                'assigned_warehouse_id' => $faker->numberBetween(1, Warehouse::count()),
                'status' => 'active',
            ]);
        }

        for ($r = 1; $r <= $drones; $r++) {
            Drone::create([
                'type' => $type = $faker->randomElement(['storage', 'maintenance']),
                'battery_life' => $faker->numberBetween(14, 28),
                'capacity' => $type == 'maintenance' ? 0 : $faker->numberBetween(5, 10) * 100,
                'assigned_warehouse_id' => $faker->numberBetween(1, Warehouse::count()),
                'status' => $faker->boolean(95) ? 'active' : 'repairing',
            ]);
        }
    }
}
