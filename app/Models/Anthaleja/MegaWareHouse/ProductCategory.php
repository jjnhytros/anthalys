<?php

namespace App\Models\Anthaleja\MegaWareHouse;

use Illuminate\Database\Eloquent\Model;

class ProductCategory extends Model
{
    protected $table = 'product_categories';

    protected $fillable = [
        'name',                // Nome della categoria (es. Prodotti deperibili)
        'description',         // Descrizione della categoria
        'parent_category_id',  // ID della categoria padre (per le sottocategorie)
        'warehouse_id',        // ID del magazzino associato alla categoria
    ];

    public function warehouse()
    {
        return $this->belongsTo(Warehouse::class, 'warehouse_id');
    }

    public function parentCategory()
    {
        return $this->belongsTo(ProductCategory::class, 'parent_category_id');
    }

    public function subCategories()
    {
        return $this->hasMany(ProductCategory::class, 'parent_category_id');
    }

    // Aggiungi ulteriori relazioni o metodi per la gestione delle categorie
}
