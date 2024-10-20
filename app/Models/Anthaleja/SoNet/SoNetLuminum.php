<?php

namespace App\Models\Anthaleja\SoNet;

use App\Models\Anthaleja\Character\Character;
use Illuminate\Database\Eloquent\Model;

class SoNetLuminum extends Model
{
    protected $table = 'lumina';

    protected $fillable = [
        'character_id',
        'luminable_type',
        'luminable_id',
    ];

    public function character()
    {
        return $this->belongsTo(Character::class);
    }

    public function luminable()
    {
        return $this->morphTo();
    }
}
