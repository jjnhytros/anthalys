<?php

namespace Database\Seeders\Anthaleja;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class RelationshipNamesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run()
    {
        $relationshipNames = [
            ['name' => 'Parent', 'required_existing' => null, 'override' => null],
            ['name' => 'Child', 'required_existing' => null, 'override' => null],
            ['name' => 'Sibling', 'required_existing' => null, 'override' => null],
            ['name' => 'Spouse', 'required_existing' => null, 'override' => null],
            ['name' => 'Divorce', 'required_existing' => 'Spouse', 'override' => 'Spouse'],
            ['name' => 'Widowed', 'required_existing' => 'Spouse', 'override' => 'Spouse'],
            ['name' => 'Friend', 'required_existing' => null, 'override' => null],
            ['name' => 'Colleague', 'required_existing' => null, 'override' => null],
            ['name' => 'Partner', 'required_existing' => null, 'override' => null],
            ['name' => 'Ex-Partner', 'required_existing' => 'Partner', 'override' => 'Partner'],
            ['name' => 'Estranged', 'required_existing' => null, 'override' => null],
            ['name' => 'Step-Parent', 'required_existing' => null, 'override' => null],
            ['name' => 'Acquaintance', 'required_existing' => null, 'override' => null],
            ['name' => 'Mentor', 'required_existing' => null, 'override' => null],
            ['name' => 'Student', 'required_existing' => null, 'override' => null],
            ['name' => 'Cousin', 'required_existing' => null, 'override' => null],
            ['name' => 'Uncle/Aunt', 'required_existing' => null, 'override' => null],
            ['name' => 'Nephew/Niece', 'required_existing' => null, 'override' => null],
            ['name' => 'Grandparent', 'required_existing' => null, 'override' => null],
            ['name' => 'Grandchild', 'required_existing' => null, 'override' => null],
            ['name' => 'Fiancé/Fiancée', 'required_existing' => null, 'override' => null],
            ['name' => 'Adopt', 'required_existing' => 'Parent', 'override' => 'Parent'],
        ];

        // Insert relationship names into the database
        DB::table('relationship_names')->insert($relationshipNames);
    }
}
