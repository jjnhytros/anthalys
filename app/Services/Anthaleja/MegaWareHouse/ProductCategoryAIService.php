<?php

namespace App\Services\Anthaleja\MegaWareHouse;

use App\Models\Anthaleja\MegaWareHouse\WarehouseLevel;
use App\Models\Anthaleja\MegaWareHouse\ProductCategory;

class ProductCategoryAIService
{
    public function generateProductCategories()
    {
        // Analizza i livelli del magazzino
        $levels = WarehouseLevel::all();
        foreach ($levels as $level) {
            // Logica per assegnare categorie basata sulla profondità
            if ($level->depth <= 6) {
                // Livelli superiori -> Alimentari o prodotti deperibili
                ProductCategory::create([
                    'macro_category' => 'Alimentari',
                    'category' => 'Freschi',
                    'subcategory' => 'Frutta',
                ]);
            } elseif ($level->depth > 6 && $level->depth <= 12) {
                // Livelli intermedi -> Tecnologia o surgelati
                ProductCategory::create([
                    'macro_category' => 'Tecnologia',
                    'category' => 'Elettrodomestici',
                    'subcategory' => 'Televisori',
                ]);
            } else {
                // Livelli profondi -> Prodotti ingombranti o non deperibili
                ProductCategory::create([
                    'macro_category' => 'Arredamento',
                    'category' => 'Mobili',
                    'subcategory' => 'Divani',
                ]);
            }
        }
        return 'Categorie generate con successo';
    }
}
