<?php

namespace Database\Seeders\ATHDateTime;

use App\Models\ATHDateTime\Day;
use Illuminate\Database\Seeder;
use App\Models\ATHDateTime\Month;

class DayMonthSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run()
    {
        // Seed Days
        $days = ['Nijahr', 'Majahr', 'Bejahr', 'Ĉejahr', 'Dyjahr', 'Fejahr', 'Ĝejahr'];

        foreach ($days as $day) {
            Day::create([
                'name' => $day,
            ]);
        }

        // Seed Months
        Month::create(['name' => 'Arejal', 'multiplier' => 0.85]);
        Month::create(['name' => 'Brukom', 'multiplier' => 0.90]);
        Month::create(['name' => 'Ĉelal', 'multiplier' => 0.95]);
        Month::create(['name' => 'Kebor', 'multiplier' => 1.00]);
        Month::create(['name' => 'Duvol', 'multiplier' => 1.05]);
        Month::create(['name' => 'Elumag', 'multiplier' => 1.10]);
        Month::create(['name' => 'Fydrin', 'multiplier' => 1.15]);
        Month::create(['name' => 'Ĝinuril', 'multiplier' => 1.20]);
        Month::create(['name' => 'Itrekos', 'multiplier' => 1.15]);
        Month::create(['name' => 'Jebrax', 'multiplier' => 1.10]);
        Month::create(['name' => 'Letranat', 'multiplier' => 1.05]);
        Month::create(['name' => 'Mulfus', 'multiplier' => 1.00]);
        Month::create(['name' => 'Nylumer', 'multiplier' => 0.95]);
        Month::create(['name' => 'Otlevat', 'multiplier' => 0.90]);
        Month::create(['name' => 'Prax', 'multiplier' => 0.85]);
        Month::create(['name' => 'Retlixen', 'multiplier' => 0.80]);
        Month::create(['name' => 'Sajep', 'multiplier' => 0.85]);
        Month::create(['name' => 'Xetul', 'multiplier' => 0.90]);
    }
}
