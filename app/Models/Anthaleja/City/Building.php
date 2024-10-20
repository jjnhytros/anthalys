<?php

namespace App\Models\Anthaleja\City;

use Illuminate\Database\Eloquent\Model;

class Building extends Model
{
    protected $fillable = ['map_square_id', 'name', 'type', 'description', 'is_main_structure'];

    // Relazione con la posizione sulla mappa
    public function mapSquare()
    {
        return $this->belongsTo(MapSquare::class);
    }
}
