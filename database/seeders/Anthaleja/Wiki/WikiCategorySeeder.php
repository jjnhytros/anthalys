<?php

namespace Database\Seeders\Anthaleja\Wiki;

use Illuminate\Database\Seeder;
use App\Models\Anthaleja\Wiki\WikiCategory;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class WikiCategorySeeder extends Seeder
{
    public function run()
    {
        // Definizione di alcune categorie di esempio
        $categories = [
            [
                'name' => 'Technology',
                'slug' => 'technology',
                'description' => 'All about the latest in technology.'
            ],
            [
                'name' => 'Science',
                'slug' => 'science',
                'description' => 'Exploring the world of science.'
            ],
            [
                'name' => 'Art',
                'slug' => 'art',
                'description' => 'A collection of articles on art.'
            ],
            [
                'name' => 'History',
                'slug' => 'history',
                'description' => 'Learn about historical events and figures.'
            ],
            [
                'name' => 'Literature',
                'slug' => 'literature',
                'description' => 'Dive into the world of literature.'
            ]
        ];

        // Inserisci le categorie nel database
        foreach ($categories as $category) {
            WikiCategory::create($category);
        }
    }
}
