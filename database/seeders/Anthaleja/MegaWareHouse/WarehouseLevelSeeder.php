<?php

namespace Database\Seeders\Anthaleja\MegaWareHouse;

use Illuminate\Database\Seeder;
use App\Models\Anthaleja\MegaWareHouse\WarehouseLevel;

class WarehouseLevelSeeder extends Seeder
{
    public function run()
    {
        $total_depth = 1728; // Profondità totale da raggiungere
        $floor_thickness_standard = 0.60; // Spessore del pavimento standard
        $floor_thickness_last = 0.10; // Spessore del pavimento per l'ultimo livello
        $current_depth = 0; // Profondità attuale
        $level_number = 0; // Numero del livello
        $level_depths = [3, 6, 12, 24]; // Profondità possibili per i livelli
        $levels = []; // Memorizza i livelli

        while ($current_depth < $total_depth) {
            // Determina la profondità del livello successivo
            $depth = ($level_number == 0) ? 3 : $level_depths[array_rand($level_depths)];

            // Calcola la profondità aggiungendo lo spessore del pavimento (tranne per il livello 0)
            $floor_thickness = ($current_depth + $depth + $floor_thickness_standard < $total_depth)
                ? $floor_thickness_standard
                : $floor_thickness_last;

            // Verifica se aggiungere l'ultimo livello con la somma esatta
            if ($current_depth + $depth + $floor_thickness > $total_depth) {
                $depth = $total_depth - $current_depth - $floor_thickness_last; // Ultima profondità
                $floor_thickness = $floor_thickness_last; // Pavimento finale di 0.10 metri
            }

            // Aggiungi il livello al magazzino
            WarehouseLevel::create([
                'warehouse_id' => 1, // ID del magazzino
                'depth' => $depth,
                'grid_size' => '36x36',
                'level_name' => "Livello $level_number",
            ]);

            // Aggiorna la profondità attuale, includendo il pavimento tra i livelli
            $current_depth += $depth + ($level_number > 0 ? $floor_thickness : 0);

            $level_number++;
        }

        // Correzione se la profondità totale è leggermente diversa
        if ($current_depth > $total_depth) {
            echo "Correzione necessaria, profondità totale superata di " . ($current_depth - $total_depth) . " metri.";
        } elseif ($current_depth < $total_depth) {
            echo "Correzione necessaria, profondità inferiore di " . ($total_depth - $current_depth) . " metri.";
        }
    }
}
