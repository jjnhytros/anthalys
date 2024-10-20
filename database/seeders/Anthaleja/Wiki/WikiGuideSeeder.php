<?php

namespace Database\Seeders\Anthaleja\Wiki;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class WikiGuideSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run()
    {
        $sql = public_path('csv/wiki.sql');
        DB::unprepared(
            file_get_contents($sql)
        );
    }
}
