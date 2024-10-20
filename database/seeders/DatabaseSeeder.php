<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call([
            Anthaleja\RoleSeeder::class,
            Anthaleja\UserCharacterSeeder::class,
            Anthaleja\ApplicationSeeder::class,
            // Anthaleja\ItemsSeeder::class,
            // Anthaleja\RelationshipNamesSeeder::class,
            // ATHDateTime\DayMonthSeeder::class,
            // Anthaleja\MapSquareSeeder::class,
            // Anthaleja\BuildingSeeder::class,
            // Anthaleja\BusLineSeeder::class,
            // Anthaleja\MetroLineSeeder::class,

            // Anthaleja\MegaWareHouse\WarehouseSeeder::class,
            // Anthaleja\MegaWareHouse\TransportSeeder::class,
            // Anthaleja\MegaWareHouse\WarehouseLevelSeeder::class,
            // Anthaleja\MegaWareHouse\WarehouseMatrixSeeder::class,

            // ATHDateTime\ProvinceSeeder::class,
            // ATHDateTime\TimezoneSeeder::class,
            // Anthaleja\InfoboxTemplateSeeder::class,
            // Anthaleja\Wiki\WikiGuideSeeder::class,
            Anthaleja\Wiki\WikiCategorySeeder::class,
            Anthaleja\Wiki\WikiArticleSeeder::class,
            // LessonSeeder::class,
            // RecipeSeeder::class,
        ]);
    }
}
