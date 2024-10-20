<?php

namespace App\Models\Anthaleja\SoNet;

use Illuminate\Database\Eloquent\Model;
use App\Models\Anthaleja\Character\Character;

class SoNetAd extends Model
{
    protected $fillable = [
        'character_id',
        'content',
        'cost',
        'type',
        'views',
        'clicks',
        'start_date',
        'end_date',
        'active'
    ];

    public function character()
    {
        return $this->belongsTo(Character::class);
    }

    // Calcola i costi per PPV e PPC
    public function calculateCosts()
    {
        $cost = 0;

        if ($this->type == 'ppv') {
            $cost = $this->views * 0.01; // Esempio: 0.01 Athel per visualizzazione
        } elseif ($this->type == 'ppc') {
            $cost = $this->clicks * 0.12; // Esempio: 0.12 Athel per click (senza includere la tassa fissa)
        }

        return $cost;
    }
}
