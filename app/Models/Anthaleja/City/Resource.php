<?php

namespace App\Models\Anthaleja\City;

use App\Models\Anthaleja\Character\Character;
use Illuminate\Database\Eloquent\Model;

class Resource extends Model
{
    protected $fillable = ['character_id', 'type', 'amount', 'description', 'max_amount'];

    // Definisce la relazione con il modello Character
    public function character()
    {
        return $this->belongsTo(Character::class);
    }
}
