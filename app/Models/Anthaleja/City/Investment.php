<?php

namespace App\Models\Anthaleja\City;

use App\Models\Anthaleja\Character\Character;
use Illuminate\Database\Eloquent\Model;

class Investment extends Model
{
    protected $fillable = [
        'character_id',
        'amount',
        'types',
        'current_value',
        'return_rate',
        'duration',
        'status',
        'stipulated_at',
        'completed_at'
    ];

    // Imposta una relazione con il modello Character
    public function character()
    {
        return $this->belongsTo(Character::class);
    }
}
