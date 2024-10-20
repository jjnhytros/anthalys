<?php

namespace App\Models\Anthaleja\MegaWareHouse;

use Illuminate\Database\Eloquent\Model;

class WarehouseLevel extends Model
{
    protected $table = 'warehouse_levels';

    protected $fillable = [
        'warehouse_id',        // ID del magazzino a cui appartiene il livello
        'depth',               // Profondità del livello in metri
        'grid_size',           // Dimensione della griglia (es. 36x36)
        'is_operational',      // Se il livello è operativo o meno
        'level_name',          // Nome del livello
    ];

    public function warehouse()
    {
        return $this->belongsTo(Warehouse::class, 'warehouse_id');
    }
}
