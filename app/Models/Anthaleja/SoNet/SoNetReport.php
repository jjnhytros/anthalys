<?php

namespace App\Models\Anthaleja\SoNet;

use App\Models\Anthaleja\Character\Character;
use Illuminate\Database\Eloquent\Model;

class SoNetReport extends Model
{
    protected $fillable = [
        'character_id',
        'reportable_id',
        'reportable_type',
        'reason',
        'status',
    ];

    /**
     * Relazione con il modello Character.
     */
    public function character()
    {
        return $this->belongsTo(Character::class);
    }

    /**
     * Relazione polymorph con i modelli SonetPost e SonetComment.
     */
    public function reportable()
    {
        return $this->morphTo();
    }
}
