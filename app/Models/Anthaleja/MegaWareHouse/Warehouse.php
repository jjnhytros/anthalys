<?php

namespace App\Models\Anthaleja\MegaWareHouse;

use Illuminate\Database\Eloquent\Model;

class Warehouse extends Model
{
    protected $table = 'warehouses';

    protected $fillable = [
        'depth',               // Profondità del magazzino in metri
        'capacity',            // Capacità massima in unità di prodotto
        'current_stock',       // Quantità attuale di merci
        'automation_level',    // Livello di automazione (0-100%)
        'security_level',      // Livello di sicurezza (0-100%)
        'energy_consumption',  // Consumo energetico
        'location',            // Posizione descrittiva o coordinate
    ];

    public function levels()
    {
        return $this->hasMany(WarehouseLevel::class, 'warehouse_id');
    }

    public function drones()
    {
        return $this->hasMany(Drone::class, 'warehouse_id');
    }

    public function robots()
    {
        return $this->hasMany(Robot::class, 'warehouse_id');
    }
}
