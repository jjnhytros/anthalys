<?php

use Illuminate\Database\Eloquent\Model;
use App\Models\Anthaleja\Marketplace\Item;

class CraftingRecipe extends Model
{
    // Definisce i campi che possono essere riempiti tramite mass assignment
    protected $fillable = [
        'item_id',             // ID dell'oggetto creato tramite questa ricetta
        'resources_required'   // Risorse necessarie per creare l'oggetto
    ];

    /**
     * Relazione tra la ricetta e l'oggetto (item).
     * Una ricetta di crafting appartiene a un oggetto che può essere creato con essa.
     */
    public function item()
    {
        // Definisce la relazione many-to-one tra la ricetta e l'oggetto
        return $this->belongsTo(Item::class);
    }
}
