<?php

namespace App\Models\Anthaleja\MegaWareHouse;

use Illuminate\Database\Eloquent\Model;

class Robot extends Model
{
    protected $table = 'robots';

    protected $fillable = [
        'warehouse_id',        // ID del magazzino a cui appartiene il robot
        'task_type',           // Tipo di task (rifornimento, spedizione, manutenzione)
        'status',              // Stato del robot (attivo, in manutenzione, ecc.)
        'battery_level',       // Livello della batteria (percentuale da 0 a 100)
    ];

    public function warehouse()
    {
        return $this->belongsTo(Warehouse::class, 'warehouse_id');
    }
}
