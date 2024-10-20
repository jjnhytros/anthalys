<?php

namespace Database\Seeders\ATHDateTime;

use Illuminate\Database\Seeder;
use App\Models\ATHDateTime\Province;

class ProvinceSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $csvPath = public_path('csv/provinces.csv');
        if (!file_exists($csvPath) || !is_readable($csvPath)) {
            $this->command->error("Il file CSV non esiste o non è leggibile: $csvPath");
            return;
        }
        if (($handle = fopen($csvPath, 'r')) !== false) {
            $header = null;
            while (($row = fgetcsv($handle, 1000, ',')) !== false) {
                if (!$header) {
                    $header = $row;
                    continue;
                }

                // Combina header e row per creare un array associativo
                $data = array_combine($header, $row);

                // Inserisci i dati nel database
                Province::create([
                    'id' => $data['id'],
                    'province' => $data['province'],
                    'full_name' => $data['full_name'],
                    'form' => $data['form'],
                    'state' => $data['state'],
                    'color' => $data['color'],
                    'capital' => $data['capital'] ?: null,
                    'area_km2' => $data['area_km2'],
                    'population_total' => $data['population_total'],
                    'population_rural' => $data['population_rural'],
                    'population_urban' => $data['population_urban'],
                    'burgs' => $data['burgs'],
                ]);
            }
            fclose($handle);
        }

        $this->command->info('Importazione del CSV completata con successo!');
    }
}
