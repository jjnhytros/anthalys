<?php

namespace App\Models\Anthaleja\City;

use App\Models\Anthaleja\Character\Character;
use Illuminate\Database\Eloquent\Model;

class Property extends Model
{
    protected $fillable = [
        'character_id',
        'map_square_id',
        'type',
        'price',
        'families_count',
        'status'
    ];

    public function character()
    {
        return $this->belongsTo(Character::class);
    }

    public function mapSquare()
    {
        return $this->belongsTo(MapSquare::class);
    }
}
