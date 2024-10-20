<?php

namespace App\Models\Anthaleja\SoNet;

use Illuminate\Database\Eloquent\Model;
use App\Models\Anthaleja\Character\Character;

class SoNetActivity extends Model
{
    protected $fillable = ['character_id', 'activity_type', 'content'];

    public function character()
    {
        return $this->belongsTo(Character::class);
    }
}
