<?php

namespace App\Models\Anthaleja\MegaWareHouse;

use Illuminate\Database\Eloquent\Model;

class Drone extends Model
{
    protected $table = 'drones';

    protected $fillable = [
        'warehouse_id',        // ID del magazzino a cui appartiene il drone
        'delivery_capacity',   // Capacità di carico del drone
        'battery_life',        // Vita della batteria (percentuale da 0 a 100)
        'status',              // Stato del drone (attivo, in manutenzione, ecc.)
        'range',               // Raggio d'azione del drone
    ];

    public function warehouse()
    {
        return $this->belongsTo(Warehouse::class, 'warehouse_id');
    }
}
