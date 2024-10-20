<?php

namespace Database\Seeders\Anthaleja\MegaWareHouse;

use App\Models\Anthaleja\MegaWareHouse\WarehouseLevel;
use Illuminate\Database\Seeder;
use App\Models\Anthaleja\MegaWareHouse\WarehouseMatrix;

class WarehouseMatrixSeeder extends Seeder
{
    public function run()
    {
        $batchSize = 2400; // Numero di record per batch
        $data = [];

        foreach (WarehouseLevel::where('depth', '>', 0)->get() as $level) {
            foreach (range(0, 35) as $x) {
                foreach (range(0, 35) as $y) {
                    $data[] = [
                        'warehouse_id' => $level->warehouse_id,
                        'level_id' => $level->id,
                        'x' => $x,
                        'y' => $y,
                        'value' => json_encode(['item' => 'item' . rand(1, 100), 'quantity' => rand(1, 50)]),
                        'created_at' => now(),
                        'updated_at' => now(),
                    ];

                    // Se raggiungi il batch size, inserisci e svuota l'array
                    if (count($data) >= $batchSize) {
                        WarehouseMatrix::insert($data);
                        $data = [];
                    }
                }
            }
        }

        // Inserisci gli ultimi record se rimasti
        if (count($data) > 0) {
            WarehouseMatrix::insert($data);
        }
    }
}
