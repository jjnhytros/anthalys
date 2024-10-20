<?php

namespace Database\Seeders\Anthaleja;

use Illuminate\Database\Seeder;
use App\Models\Anthaleja\Character;
use App\Models\Anthaleja\Bank\Investment;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class InvestmentSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run()
    {
        $character = Character::first();

        // Crea investimenti di test
        Investment::create([
            'character_id' => $character->id,
            'amount' => 1000,
            'type' => 'low_risk',
            'return_rate' => 0.02,
            'duration' => 30,
            'status' => 'active',
        ]);

        Investment::create([
            'character_id' => $character->id,
            'amount' => 5000,
            'type' => 'medium_risk',
            'return_rate' => 0.05,
            'duration' => 60,
            'status' => 'completed',
            'completed_at' => now()->subDays(10),
        ]);
    }
}
