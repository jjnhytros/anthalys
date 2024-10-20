<?php

namespace Database\Seeders\ATHDateTime;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use App\Models\ATHDateTime\Province;
use App\Models\ATHDateTime\Timezone;

class TimezoneSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run()
    {
        $provinces = Province::all(); // Assuming you have a table for provinces
        $id = 1;
        $timezones = [];
        $usedAbbreviations = [];

        // Instantiate the Timezone model
        $timezoneModel = new Timezone();

        foreach ($provinces as $province) {
            $provinceName = $province->province;
            $state = $province->state;
            $fullName = $province->full_name;
            $identifier = "{$state}/{$provinceName}";

            // Generate abbreviation and country code
            $abbreviation = $timezoneModel->generateAbbreviation($provinceName, $usedAbbreviations);
            $country_code = $timezoneModel->getCountryCode($state);

            // Generate latitude and longitude (special case for Anthalys)
            $latitude = $abbreviation == 'ATH' ? 12.196824 : mt_rand(-90000000, 90000000) / 1000000;
            $longitude = $abbreviation == 'ATH' ? 0 : mt_rand(-180000000, 180000000) / 1000000;

            // Calculate offset_hours based on longitude
            $degrees = 360 / 28;
            $offset_hours = round($longitude / $degrees);

            // Add to timezones array
            $timezones[] = [
                'id' => $id,
                'identifier' => $identifier,
                'abbreviation' => $abbreviation,
                'offset_hours' => $offset_hours,
                'country_code' => $country_code,
                'latitude' => $latitude,
                'longitude' => $longitude,
                'comments' => $fullName,
            ];

            $id++;
        }

        // Insert into the database
        DB::table('anthal_timezones')->insert($timezones);
        Timezone::create(['identifier' => 'AST', 'abbreviation' => 'AST', 'offset_hours' => 0, 'country_code' => 'AST', 'latitude' => 0, 'longitude' => 0, 'comments' => 'AST']);
    }
}
