<?php

namespace App\Models\Anthaleja\MegaWareHouse;

use Illuminate\Database\Eloquent\Model;

class WarehouseMatrix extends Model
{
    protected $table = 'warehouse_matrices';

    protected $fillable = [
        'warehouse_id',
        'level_id',
        'x',
        'y',
        'value',
    ];

    // Casting per il campo JSONB
    protected $casts = [
        'value' => 'array',
    ];

    // Relazione con il magazzino
    public function warehouse()
    {
        return $this->belongsTo(Warehouse::class);
    }

    // Relazione con il livello del magazzino
    public function warehouseLevel()
    {
        return $this->belongsTo(WarehouseLevel::class, 'level_id');
    }
}
