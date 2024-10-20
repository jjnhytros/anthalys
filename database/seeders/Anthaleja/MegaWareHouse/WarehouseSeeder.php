<?php

namespace Database\Seeders\Anthaleja\MegaWareHouse;

use Illuminate\Database\Seeder;
use App\Models\Anthaleja\MegaWareHouse\Warehouse;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class WarehouseSeeder extends Seeder
{
    public function run()
    {
        Warehouse::create([
            'depth' => 500,
            'capacity' => 10000,
            'current_stock' => 2000,
            'automation_level' => 80,
            'security_level' => 90,
            'energy_consumption' => 120.5,
            'location' => 'Zona A1',
        ]);

        Warehouse::create([
            'depth' => 1000,
            'capacity' => 20000,
            'current_stock' => 5000,
            'automation_level' => 95,
            'security_level' => 98,
            'energy_consumption' => 250.0,
            'location' => 'Zona B2',
        ]);
    }
}
