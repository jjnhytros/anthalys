<?php

namespace Database\Seeders\Anthaleja;

use App\Models\User;
use Faker\Factory as Faker;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Illuminate\Support\Facades\Hash;
use App\Models\Anthaleja\Character\Character;

class UserCharacterSeeder extends Seeder
{
    public function run()
    {
        $attributesZero = ['charisma' => 0, 'empathy' => 0, 'leadership' => 0, 'persuasion' => 0, 'collaboration' => 0, 'wealth' => 0,];
        $attributesFive = ['charisma' => 5, 'empathy' => 5, 'leadership' => 5, 'persuasion' => 5, 'collaboration' => 5, 'wealth' => 5,];

        $roles = ['admin', 'government', 'player'];
        foreach ($roles as $role) {
            Role::firstOrCreate(['name' => $role]);
        }

        $faker = Faker::create();

        $admin = User::create([
            'username' => 'admin',
            'email' => 'admin@anthaleja.ath',
            'password' => Hash::make('password'),
        ]);
        // $admin->assignRole('admin');

        $c = Character::create([
            'user_id' => $admin->id,
            'first_name' => 'Admin',
            'last_name' => 'Istrator',
            'username' => 'admin',
            'email' => 'admin@anthaleja.ath',
            // 'attributes' => json_encode($attributesZero),
            'cash' => 0,
            'bank' => 0,
            'bank_account' => 'ATH-00000000',
            'have_phone' => true,
            'phone_number' => '400-' . $faker->numberBetween(100, 999) . ' ' . $faker->numberBetween(1000, 9999),
            'status' => true,
        ]);
        $c->profile()->create(['night_mode' => false]);


        $government = User::create([
            'username' => 'government',
            'email' => 'government@anthaleja.ath',
            'password' => Hash::make('password'),
        ]);
        // $government->assignRole('government');

        $c = Character::create([
            'user_id' => $government->id,
            'first_name' => 'Anthal',
            'last_name' => 'Government',
            'username' => 'government',
            // 'email' => 'admin@anthaleja.ath',
            // 'attributes' => json_encode($attributesFive),
            'cash' => 6e24,
            'bank' => 6e24,
            'bank_account' => 'ATH-0' . $faker->numberBetween(1000000, 9999999),
            'have_phone' => true,
            'phone_number' => '612-' . $faker->numberBetween(100, 999) . ' ' . $faker->numberBetween(1000, 9999),
            'status' => true,
            'is_npc' => true,
        ]);
        $c->profile()->create(['night_mode' => false]);


        $jjnhytros = User::create([
            'username' => 'jjnhytros',
            'email' => 'jjnhytros@anthaleja.ath',
            'password' => Hash::make('password'),
        ]);
        // $jjnhytros->assignRole('player');

        $c = Character::create([
            'user_id' => $jjnhytros->id,
            'first_name' => 'J.J.',
            'last_name' => 'Nhytros',
            'username' => 'jjnhytros',
            // 'email' => 'jj@anthaleja.ath',
            'cash' => 300,
            'bank' => 2500,
            'bank_account' => 'ATH-' . $faker->numberBetween(10000000, 99999999),
            'have_phone' => true,
            'phone_number' => $faker->numberBetween(100, 999) . '-' . $faker->numberBetween(100, 999) . ' ' . $faker->numberBetween(1000, 9999),
            'status' => true,
        ]);
        $c->profile()->create(['night_mode' => false]);



        $bank = User::create([
            'username' => 'bank',
            'email' => 'bank@anthaleja.ath',
            'password' => Hash::make('password'),
        ]);
        // $admin->assignRole('admin');
        $c = Character::create([
            'user_id' => $bank->id,
            'first_name' => 'Bank',
            'last_name' => 'Anthalys',
            'username' => 'athbank',
            // 'email' => 'athbank@anthaleja.ath',
            'cash' => 0,
            'bank' => 0,
            'bank_account' => 'ATH-00000001',
            'have_phone' => false,
            'phone_number' => null,
            'status' => true,
            'is_npc' => true,
        ]);
        $c->profile()->create(['night_mode' => false]);


        // Creazione players
        for ($i = 1; $i <= 20; $i++) {
            $player = User::create([
                'username' => 'player' . $i,
                'email' => 'player-' . $i . '@anthaleja.ath',
                'password' => Hash::make('password'),
            ]);
            $attributes = $this->assignRandomAttributes();
            $resources = [
                'energy',
                'hunger',
                'cleanliness',
                'sobriety',
                'health',
                'happyness',
                'stamina',
                'stress',
                'hygiene',
                'fatigue',
                'social_interaction',
                'learning'
            ];
            // $characterResources = $this->assignResourcesBasedOnAttributes($attributes, $resources);
            // dd($characterResources);
            $initialCash = $this->calculateInitialCash($attributes['wealth']);
            $initialBank = $this->calculateInitialBank($attributes['wealth']);
            // $player->assignRole('player');

            $c = Character::create([
                'user_id' => $player->id,
                'first_name' => $faker->firstName,
                'last_name' => $faker->lastName,
                'username' => $username = 'player_' . $i,
                // 'email' => $username . '@anthaleja.ath',
                // 'resources' => $characterResources,
                // 'attributes' => json_encode($attributes),
                'cash' => $initialCash,
                'bank' => $initialBank,
                'bank_account' => 'ATH-' . $faker->numberBetween(10000000, 99999999),
                'have_phone' => true,
                'phone_number' => $faker->numberBetween(100, 999) . '-' . $faker->numberBetween(100, 999) . ' ' . $faker->numberBetween(1000, 9999),
                'status' => true,
                'is_npc' => $faker->boolean(6),
            ]);
            $c->profile()->create(['night_mode' => false]);
        }
    }

    private function assignRandomAttributes()
    {
        // Definisci le caratteristiche sociali
        $attributes = [
            'charisma' => 0,
            'empathy' => 0,
            'leadership' => 0,
            'persuasion' => 0,
            'collaboration' => 0,
            'wealth' => 0, // Ricchezza
        ];

        $totalPoints = 12;
        $maxPointsPerAttribute = 5;

        // Array per gli attributi rimanenti
        $remainingAttributes = array_keys($attributes);

        while ($totalPoints > 0 && count($remainingAttributes) > 0) {
            // Scegli un attributo casuale da riempire
            $randomAttribute = $remainingAttributes[array_rand($remainingAttributes)];

            // Genera un valore casuale per l'attributo, con il limite dei punti rimanenti e il massimo per attributo
            $randomValue = rand(0, min($totalPoints, $maxPointsPerAttribute));

            // Assegna il valore all'attributo
            $attributes[$randomAttribute] = $randomValue;

            // Riduci i punti totali
            $totalPoints -= $randomValue;

            // Rimuovi l'attributo dall'elenco se è stato assegnato il valore massimo
            if ($attributes[$randomAttribute] === $maxPointsPerAttribute || $totalPoints === 0) {
                unset($remainingAttributes[array_search($randomAttribute, $remainingAttributes)]);
            }
        }

        return $attributes;
    }

    private function assignResourcesBasedOnAttributes($attributes, $resources)
    {
        // dd($attributes, $resources);
        $characterResources = [];

        foreach ($resources as $resource) {
            switch ($resource) {
                case 'energy':
                    $characterResources[$resource] = 50 + ($resource['stamina'] * 10);
                    break;
                case 'hunger':
                    $characterResources[$resource] = 100 - ($attributes['collaboration'] * 5);
                    break;
                case 'cleanliness':
                    $characterResources[$resource] = 70 + ($attributes['empathy'] * 5);
                    break;
                case 'sobriety':
                    $characterResources[$resource] = 80 + ($attributes['persuasion'] * 3);
                    break;
                case 'health':
                    $characterResources[$resource] = 90 + ($attributes['wealth'] * 10);
                    break;
                case 'happyness':
                    $characterResources[$resource] = 50 + ($attributes['charisma'] * 8);
                    break;
                case 'stamina':
                    $characterResources[$resource] = 60 + ($attributes['leadership'] * 7);
                    break;
                case 'stress':
                    $characterResources[$resource] = 100 - ($attributes['leadership'] * 6);
                    break;
                case 'hygiene':
                    $characterResources[$resource] = 70 + ($resource['cleanliness'] * 4);
                    break;
                case 'fatigue':
                    $characterResources[$resource] = 50 + ($resource['stamina'] * 6);
                    break;
                case 'social_interaction':
                    $characterResources[$resource] = 60 + ($attributes['collaboration'] * 5);
                    break;
                case 'learning':
                    $characterResources[$resource] = 50 + ($attributes['empathy'] * 6);
                    break;
                default:
                    $characterResources[$resource] = 50;
                    break;
            }
        }

        return $characterResources;
    }

    private function calculateInitialCash($wealth)
    {
        $baseCash = 0; // Valore di contanti base
        $cash = $baseCash + ($wealth * (rand(1, 5) * 10)); // Aggiungi un valore aggiuntivo basato sulla ricchezza

        return $cash;
    }
    private function calculateInitialBank($wealth)
    {
        $baseBank = 500; // Valore in banca base
        $bank = $baseBank + ($wealth * (rand(5, 10) * 10)); // Aggiungi un valore aggiuntivo basato sulla ricchezza

        return $bank;
    }
}
